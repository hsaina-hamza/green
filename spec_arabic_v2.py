from fpdf import FPDF
import os
import glob
import arabic_reshaper
from bidi.algorithm import get_display
from fpdf.enums import XPos, YPos

class ArabicPDF(FPDF):
    def __init__(self):
        super().__init__()
        self.set_auto_page_break(auto=True, margin=15)

    def arabic_text(self, txt):
        reshaped_text = arabic_reshaper.reshape(txt)
        bidi_text = get_display(reshaped_text)
        return bidi_text

    def arabic_cell(self, w, h, txt, border=0, align='R', new_x=XPos.LMARGIN, new_y=YPos.NEXT):
        self.cell(w, h, self.arabic_text(txt), border, new_x=new_x, new_y=new_y, align=align)

    def arabic_multi_cell(self, w, h, txt, border=0, align='R'):
        self.multi_cell(w, h, self.arabic_text(txt), border, align=align)

def generate_spec():
    pdf = ArabicPDF()
    pdf.add_page()
    pdf.set_font("Helvetica", size=16)
    
    # Title
    pdf.arabic_cell(0, 10, "مواصفات المشروع", align='C')
    pdf.ln(10)
    
    # Database Tables
    pdf.set_font("Helvetica", size=12)
    pdf.arabic_cell(0, 10, "قاعدة البيانات:")
    pdf.ln(5)
    
    migrations = glob.glob('database/migrations/*.php')
    for file in migrations:
        pdf.arabic_cell(0, 10, f"• {os.path.basename(file)}")
        pdf.ln(5)
    
    # Views
    pdf.add_page()
    pdf.arabic_cell(0, 10, "الصفحات:")
    pdf.ln(5)
    
    views = []
    for root, dirs, files in os.walk('resources/views'):
        for file in files:
            if file.endswith('.blade.php'):
                views.append(os.path.join(root, file).replace('resources/views/', ''))
    
    for view in sorted(views):
        pdf.arabic_cell(0, 10, f"• {view}")
        pdf.ln(5)
    
    # Models
    pdf.add_page()
    pdf.arabic_cell(0, 10, "النماذج:")
    pdf.ln(5)
    
    models = glob.glob('app/Models/*.php')
    for file in models:
        model_name = os.path.basename(file).replace('.php', '')
        with open(file, 'r', encoding='utf-8') as f:
            content = f.read()
            relationships = []
            if 'belongsTo' in content:
                relationships.append('belongsTo')
            if 'hasMany' in content:
                relationships.append('hasMany')
            if relationships:
                pdf.arabic_cell(0, 10, f"• {model_name} ({', '.join(relationships)})")
            else:
                pdf.arabic_cell(0, 10, f"• {model_name}")
            pdf.ln(5)
    
    # Technical Requirements
    pdf.add_page()
    pdf.arabic_cell(0, 10, "المتطلبات التقنية:")
    pdf.ln(10)
    
    requirements = [
        "المتطلبات الأساسية:",
        "• Laravel 10.x",
        "• PHP 8.2",
        "• MySQL 8.0",
        "• Node.js & NPM",
        "• Composer",
        "",
        "المكتبات والإضافات:",
        "• Tailwind CSS",
        "• Alpine.js",
        "• Spatie Permissions",
        "• Leaflet.js للخرائط",
        "• FPDF للتقارير",
        "• Laravel Sanctum للمصادقة"
    ]
    
    for req in requirements:
        pdf.arabic_cell(0, 10, req)
        pdf.ln(5)
    
    pdf.output("project_specification.pdf")

if __name__ == "__main__":
    generate_spec()
