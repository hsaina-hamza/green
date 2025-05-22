from fpdf import FPDF
import os
import glob
from fpdf.enums import XPos, YPos

class BilingualPDF(FPDF):
    def __init__(self):
        super().__init__()
        self.set_auto_page_break(auto=True, margin=15)

    def chapter_title(self, en_text, ar_text=""):
        self.set_font("Helvetica", size=16)
        self.cell(0, 10, en_text, new_x=XPos.LMARGIN, new_y=YPos.NEXT, align='L')
        if ar_text:
            self.cell(0, 10, f"({ar_text})", new_x=XPos.LMARGIN, new_y=YPos.NEXT, align='L')
        self.ln(5)

    def chapter_body(self, text):
        self.set_font("Helvetica", size=12)
        self.multi_cell(0, 10, text)
        self.ln(5)

def extract_table_info(file_path):
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
            if 'Schema::create' in content:
                lines = content.split('\n')
                columns = []
                for line in lines:
                    if '$table->' in line and ';' in line:
                        columns.append(line.strip())
                return columns
    except:
        return []

def generate_spec():
    pdf = BilingualPDF()
    
    # Title Page
    pdf.add_page()
    pdf.chapter_title("Project Specification", "مواصفات المشروع")
    pdf.ln(10)
    
    # Project Overview
    pdf.chapter_title("Project Overview")
    pdf.chapter_body("""
    Municipal Waste Management System
    - Waste location tracking and reporting
    - Interactive mapping interface
    - Role-based access control (Admin, Worker, User)
    - Real-time status updates
    - Automated scheduling system
    """)
    
    # Database Schema
    pdf.add_page()
    pdf.chapter_title("Database Schema", "قاعدة البيانات")
    
    migrations = glob.glob('database/migrations/*.php')
    for file in migrations:
        table_info = extract_table_info(file)
        if table_info:
            pdf.chapter_body(f"\nTable: {os.path.basename(file)}")
            for column in table_info:
                pdf.chapter_body(f"  {column}")
    
    # Views & Templates
    pdf.add_page()
    pdf.chapter_title("Views & Templates", "الصفحات")
    
    views = []
    for root, dirs, files in os.walk('resources/views'):
        for file in files:
            if file.endswith('.blade.php'):
                views.append(os.path.join(root, file).replace('resources/views/', ''))
    
    for view in sorted(views):
        pdf.chapter_body(f"• {view}")
    
    # Models & Relationships
    pdf.add_page()
    pdf.chapter_title("Models & Relationships", "النماذج والعلاقات")
    
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
            if 'hasOne' in content:
                relationships.append('hasOne')
            if relationships:
                pdf.chapter_body(f"• {model_name}")
                pdf.chapter_body(f"  Relationships: {', '.join(relationships)}")
    
    # Technical Requirements
    pdf.add_page()
    pdf.chapter_title("Technical Requirements", "المتطلبات التقنية")
    pdf.chapter_body("""
    Core Requirements:
    • Laravel 10.x
    • PHP 8.2
    • MySQL 8.0
    • Node.js & NPM
    • Composer
    
    Libraries & Add-ons:
    • Tailwind CSS
    • Alpine.js
    • Spatie Permissions
    • Leaflet.js for maps
    • FPDF for reports
    • Laravel Sanctum for authentication
    """)
    
    # Features & Functionality
    pdf.add_page()
    pdf.chapter_title("Features & Functionality", "المميزات والوظائف")
    pdf.chapter_body("""
    User Management:
    • Role-based access control
    • Authentication and authorization
    • Profile management
    
    Waste Management:
    • Interactive waste location mapping
    • Report submission and tracking
    • Schedule management
    • Real-time status updates
    
    Communication:
    • Comment system
    • Notification system
    • Email notifications
    
    Analytics:
    • Dashboard with statistics
    • Report generation
    • Data visualization
    """)
    
    pdf.output("project_specification.pdf")

if __name__ == "__main__":
    generate_spec()
