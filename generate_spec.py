import os
from fpdf import FPDF
import arabic_reshaper
from bidi.algorithm import get_display
import glob

class ProjectSpecPDF(FPDF):
    def __init__(self):
        super().__init__()
        self.set_auto_page_break(auto=True, margin=15)
        self.add_font('Arial', '', 'arial.ttf', uni=True)
        self.add_font('Arial', 'B', 'arialbd.ttf', uni=True)

    def arabic_text(self, txt):
        reshaped_text = arabic_reshaper.reshape(txt)
        bidi_text = get_display(reshaped_text)
        return bidi_text

    def chapter_title(self, title):
        self.set_font('Arial', 'B', 16)
        self.cell(0, 10, self.arabic_text(title), ln=True, align='R')
        self.ln(10)

    def chapter_body(self, txt):
        self.set_font('Arial', '', 12)
        self.multi_cell(0, 10, self.arabic_text(txt))
        self.ln()

def get_migrations_info():
    migrations = []
    migration_files = glob.glob('database/migrations/*.php')
    for file in migration_files:
        with open(file, 'r', encoding='utf-8') as f:
            content = f.read()
            # Extract table name and columns
            if 'Schema::create' in content:
                table_name = content.split("Schema::create('")[1].split("'")[0]
                migrations.append({
                    'table': table_name,
                    'content': content
                })
    return migrations

def get_blade_pages():
    pages = []
    for root, dirs, files in os.walk('resources/views'):
        for file in files:
            if file.endswith('.blade.php'):
                pages.append(os.path.join(root, file).replace('resources/views/', ''))
    return pages

def get_models_info():
    models = []
    model_files = glob.glob('app/Models/*.php')
    for file in model_files:
        with open(file, 'r', encoding='utf-8') as f:
            content = f.read()
            models.append({
                'name': os.path.basename(file).replace('.php', ''),
                'content': content
            })
    return models

def generate_spec():
    pdf = ProjectSpecPDF()
    
    # Project Objective
    pdf.add_page()
    pdf.chapter_title('وصف هدف المشروع')
    pdf.chapter_body('''
    نظام إدارة النفايات البلدية هو منصة متكاملة تهدف إلى:
    • تحسين عملية إدارة النفايات في المدينة
    • تسهيل عملية الإبلاغ عن النفايات
    • تتبع حالة البلاغات والجدولة
    • توفير خريطة تفاعلية لمواقع النفايات
    • إدارة جداول جمع النفايات
    ''')

    # Database Schema
    pdf.add_page()
    pdf.chapter_title('تصميم قاعدة البيانات')
    migrations = get_migrations_info()
    for migration in migrations:
        pdf.chapter_body(f"جدول {migration['table']}")

    # Relationships
    pdf.add_page()
    pdf.chapter_title('العلاقات بين الجداول')
    models = get_models_info()
    for model in models:
        if 'belongsTo' in model['content'] or 'hasMany' in model['content']:
            pdf.chapter_body(f"نموذج {model['name']}")

    # UI Pages
    pdf.add_page()
    pdf.chapter_title('واجهات المستخدم المطلوبة')
    pages = get_blade_pages()
    for page in pages:
        pdf.chapter_body(f"• {page}")

    # Technical Requirements
    pdf.add_page()
    pdf.chapter_title('المتطلبات التقنية')
    pdf.chapter_body('''
    • Laravel 10.x
    • PHP 8.2
    • MySQL 8.0
    • Node.js & NPM
    • Composer
    • Tailwind CSS
    • Alpine.js
    • Spatie Permissions
    • Leaflet.js للخرائط
    ''')

    pdf.output('project_specification.pdf', 'F')

if __name__ == '__main__':
    generate_spec()
