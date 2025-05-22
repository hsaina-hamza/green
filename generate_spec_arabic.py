from fpdf import FPDF
import os
import glob
import arabic_reshaper
from bidi.algorithm import get_display

class PDF(FPDF):
    def __init__(self):
        super().__init__()
        self.set_auto_page_break(auto=True, margin=15)
        self.add_font('DejaVu', '', 'DejaVuSansCondensed.ttf', uni=True)
        self.add_font('DejaVu', 'B', 'DejaVuSansCondensed-Bold.ttf', uni=True)

    def arabic_cell(self, w, h, txt, border=0, align='R'):
        reshaped_text = arabic_reshaper.reshape(txt)
        bidi_text = get_display(reshaped_text)
        self.cell(w, h, bidi_text, border=border, align=align)

    def arabic_multi_cell(self, w, h, txt, border=0, align='R'):
        reshaped_text = arabic_reshaper.reshape(txt)
        bidi_text = get_display(reshaped_text)
        self.multi_cell(w, h, bidi_text, border=border, align=align)

    def chapter_title(self, title):
        self.set_font('DejaVu', 'B', 16)
        self.arabic_cell(0, 10, title, align='R')
        self.ln(15)

    def chapter_body(self, txt):
        self.set_font('DejaVu', '', 12)
        self.arabic_multi_cell(0, 10, txt, align='R')
        self.ln(5)

def get_migrations():
    migrations = []
    for file in glob.glob('database/migrations/*.php'):
        with open(file, 'r', encoding='utf-8') as f:
            content = f.read()
            if 'Schema::create' in content:
                table_name = content.split("Schema::create('")[1].split("'")[0]
                migrations.append(table_name)
    return sorted(migrations)

def get_views():
    views = []
    for root, dirs, files in os.walk('resources/views'):
        for file in files:
            if file.endswith('.blade.php'):
                views.append(os.path.join(root, file).replace('resources/views/', ''))
    return sorted(views)

def get_models():
    models = []
    for file in glob.glob('app/Models/*.php'):
        with open(file, 'r', encoding='utf-8') as f:
            content = f.read()
            model_name = os.path.basename(file).replace('.php', '')
            relationships = []
            if 'belongsTo' in content:
                relationships.append('belongsTo')
            if 'hasMany' in content:
                relationships.append('hasMany')
            if relationships:
                models.append(f"{model_name} ({', '.join(relationships)})")
    return sorted(models)

def generate_spec():
    pdf = PDF()
    
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
    migrations = get_migrations()
    for table in migrations:
        pdf.chapter_body(f"• جدول: {table}")

    # UI Pages
    pdf.add_page()
    pdf.chapter_title('واجهات المستخدم المطلوبة')
    views = get_views()
    for view in views:
        pdf.chapter_body(f"• {view}")

    # Models & Relationships
    pdf.add_page()
    pdf.chapter_title('العلاقات بين الجداول')
    models = get_models()
    for model in models:
        pdf.chapter_body(f"• {model}")

    # Technical Requirements
    pdf.add_page()
    pdf.chapter_title('المتطلبات التقنية')
    pdf.chapter_body('''
    المتطلبات الأساسية:
    • Laravel 10.x
    • PHP 8.2
    • MySQL 8.0
    • Node.js & NPM
    • Composer

    المكتبات والإضافات:
    • Tailwind CSS
    • Alpine.js
    • Spatie Permissions
    • Leaflet.js للخرائط
    • FPDF للتقارير
    • Laravel Sanctum للمصادقة
    ''')

    pdf.output('project_specification.pdf')

if __name__ == '__main__':
    generate_spec()
