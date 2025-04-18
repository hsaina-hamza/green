<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

abstract class BaseService
{
    /**
     * Execute an action within a database transaction.
     */
    protected function executeInTransaction(callable $action)
    {
        try {
            DB::beginTransaction();
            
            $result = $action();
            
            DB::commit();
            
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Transaction failed: ' . $e->getMessage(), [
                'exception' => $e,
                'service' => static::class,
            ]);
            
            throw $e;
        }
    }

    /**
     * Handle file upload.
     */
    protected function handleFileUpload($file, string $path, string $disk = 'public'): string
    {
        try {
            return $file->store($path, $disk);
        } catch (Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage(), [
                'exception' => $e,
                'service' => static::class,
            ]);
            
            throw $e;
        }
    }

    /**
     * Delete a file.
     */
    protected function deleteFile(string $path, string $disk = 'public'): bool
    {
        try {
            return Storage::disk($disk)->delete($path);
        } catch (Exception $e) {
            Log::error('File deletion failed: ' . $e->getMessage(), [
                'exception' => $e,
                'service' => static::class,
            ]);
            
            throw $e;
        }
    }

    /**
     * Create a model instance with validation.
     */
    protected function create(string $modelClass, array $data): Model
    {
        return $this->executeInTransaction(function () use ($modelClass, $data) {
            return $modelClass::create($data);
        });
    }

    /**
     * Update a model instance with validation.
     */
    protected function update(Model $model, array $data): bool
    {
        return $this->executeInTransaction(function () use ($model, $data) {
            return $model->update($data);
        });
    }

    /**
     * Delete a model instance.
     */
    protected function delete(Model $model): bool
    {
        return $this->executeInTransaction(function () use ($model) {
            return $model->delete();
        });
    }

    /**
     * Log an action.
     */
    protected function logAction(string $action, array $data = []): void
    {
        Log::info("[{$action}] performed in " . static::class, $data);
    }

    /**
     * Format a date for database.
     */
    protected function formatDateForDatabase(\DateTime $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Check if a model exists by conditions.
     */
    protected function exists(string $modelClass, array $conditions): bool
    {
        return $modelClass::where($conditions)->exists();
    }

    /**
     * Get paginated results.
     */
    protected function getPaginated(string $modelClass, array $conditions = [], array $with = [], int $perPage = 10)
    {
        $query = $modelClass::query();

        if (!empty($conditions)) {
            $query->where($conditions);
        }

        if (!empty($with)) {
            $query->with($with);
        }

        return $query->paginate($perPage);
    }
}
