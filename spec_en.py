from fpdf import FPDF
import os
import glob
from fpdf.enums import XPos, YPos

class ProjectPDF(FPDF):
    def __init__(self):
        super().__init__()
        self.set_auto_page_break(auto=True, margin=15)

    def chapter_title(self, text):
        self.set_font("Helvetica", size=16)
        self.cell(0, 10, text, new_x=XPos.LMARGIN, new_y=YPos.NEXT, align='L')
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
    pdf = ProjectPDF()
    
    # Title Page
    pdf.add_page()
    pdf.chapter_title("Green Mastar - Project Specification")
    pdf.ln(10)
    
    # Project Overview
    pdf.chapter_title("1. Project Overview")
    pdf.chapter_body("""
    Municipal Waste Management System is an integrated platform designed to:
    - Track and manage waste locations across the city
    - Enable citizen reporting of waste issues
    - Provide real-time status updates
    - Manage waste collection schedules
    - Support multiple user roles (Admin, Worker, User)
    """)
    
    # Database Schema
    pdf.add_page()
    pdf.chapter_title("2. Database Schema")
    
    migrations = glob.glob('database/migrations/*.php')
    for file in migrations:
        table_info = extract_table_info(file)
        if table_info:
            pdf.chapter_body(f"\nTable: {os.path.basename(file)}")
            for column in table_info:
                pdf.chapter_body(f"  {column}")
    
    # Views & Templates
    pdf.add_page()
    pdf.chapter_title("3. Views & Templates")
    
    view_categories = {
        'admin': [],
        'auth': [],
        'reports': [],
        'sites': [],
        'other': []
    }
    
    for root, dirs, files in os.walk('resources/views'):
        for file in files:
            if file.endswith('.blade.php'):
                path = os.path.join(root, file).replace('resources/views/', '')
                category = 'other'
                for cat in view_categories.keys():
                    if cat in path:
                        category = cat
                        break
                view_categories[category].append(path)
    
    for category, views in view_categories.items():
        if views:
            pdf.chapter_body(f"\n{category.title()} Views:")
            for view in sorted(views):
                pdf.chapter_body(f"  • {view}")
    
    # Models & Relationships
    pdf.add_page()
    pdf.chapter_title("4. Models & Relationships")
    
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
                pdf.chapter_body(f"\n{model_name}:")
                pdf.chapter_body(f"  Relationships: {', '.join(relationships)}")
    
    # Technical Requirements
    pdf.add_page()
    pdf.chapter_title("5. Technical Requirements")
    pdf.chapter_body("""
    Core Requirements:
    • Laravel 10.x
    • PHP 8.2
    • MySQL 8.0
    • Node.js & NPM
    • Composer
    
    Libraries & Add-ons:
    • Tailwind CSS for styling
    • Alpine.js for frontend interactivity
    • Spatie Permissions for role management
    • Leaflet.js for interactive maps
    • FPDF for report generation
    • Laravel Sanctum for API authentication
    """)
    
    # Features & Functionality
    pdf.add_page()
    pdf.chapter_title("6. Features & Functionality")
    pdf.chapter_body("""
    User Management:
    • Role-based access control (Admin, Worker, User)
    • User authentication and authorization
    • Profile management with contact information
    
    Waste Management:
    • Interactive waste location mapping
    • Report submission with image uploads
    • Schedule management for waste collection
    • Real-time status updates
    • Location-based waste tracking
    
    Communication:
    • Comment system on reports
    • Notification system for updates
    • Email notifications for important events
    • Worker assignment notifications
    
    Analytics & Reporting:
    • Administrative dashboard with statistics
    • Report generation and export
    • Data visualization of waste patterns
    • Schedule optimization insights
    """)
    
    pdf.output("project_specification.pdf")

if __name__ == "__main__":
    generate_spec()
