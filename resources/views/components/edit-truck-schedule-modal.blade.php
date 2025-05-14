<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <!-- Modal Header -->
        <div class="flex justify-between items-center border-b pb-3">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-truck-moving mr-2 text-blue-500"></i>
                تعديل جدول الشاحنة
            </h3>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 transition duration-300">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Edit Form -->
        <form id="editForm" method="POST" class="mt-4">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <!-- Location Field -->
                <div>
                    <label for="edit_location_id" class="block text-sm font-medium text-gray-700 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                        الموقع
                    </label>
                    <select id="edit_location_id" name="location_id" required 
                        class="mt-1 w-full py-2 px-3 rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-300">
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Day Field -->
                <div>
                    <label for="edit_day" class="block text-sm font-medium text-gray-700 flex items-center">
                        <i class="fas fa-calendar-day mr-2 text-blue-500"></i>
                        اليوم
                    </label>
                    <select id="edit_day" name="day" required 
                        class="mt-1 w-full py-2 px-3 rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-300">
                        @foreach(['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'] as $day)
                            <option value="{{ $day }}">{{ $day }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Time Field -->
                <div>
                    <label for="edit_time" class="block text-sm font-medium text-gray-700 flex items-center">
                        <i class="far fa-clock mr-2 text-blue-500"></i>
                        الوقت
                    </label>
                    <input type="time" id="edit_time" name="time" required 
                        class="mt-1 w-full py-2 px-3 rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-300">
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="mt-6 flex justify-end space-x-3 space-x-reverse">
                <button type="button" onclick="closeEditModal()" 
                    class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-300 flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    إلغاء
                </button>
                <button type="submit" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.body.style.overflow = 'auto'; // Re-enable scrolling
    }
</script>

<style>
    #editModal {
        transition: all 0.3s ease;
    }
    select, input {
        transition: all 0.3s ease;
    }
    select:focus, input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }
    button:hover {
        transform: translateY(-1px);
    }
</style>