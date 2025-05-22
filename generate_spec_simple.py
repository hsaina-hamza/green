from fpdf import FPDF
import os
import glob

class PDF(FPDF):
    def __init__(self):
        super().__init__()
        self.set_auto_page_break(auto=True, margin=15)

    def chapter_title(self, title):
        self.set_font('Arial', 'B', 16)
        self.cell(0, 10, title, ln=True)
        self.ln(10)

    def chapter_body(self, txt):
        self.set_font('Arial', '', 12)
        self.multi_cell(0, 10, txt)
        self.ln()

def get_migrations():
    migrations = []
    for file in glob.glob('database/migrations/*.php'):
        migrations.append(os.path.basename(file))
    return migrations

def get_views():
    views = []
    for root, dirs, files in os.walk('resources/views'):
        for file in files:
            if file.endswith('.blade.php'):
                views.append(os.path.join(root, file).replace('resources/views/', ''))
    return views

def get_models():
    models = []
    for file in glob.glob('app/Models/*.php'):
        models.append(os.path.basename(file))
    return models

def generate_spec():
    pdf = PDF()
    
    # Project Objective
    pdf.add_page()
    pdf.chapter_title('Project Objective')
    pdf.chapter_body('''
    Municipal Waste Management System is an integrated platform that aims to:
    - Improve waste management in the city
    - Facilitate waste reporting
    - Track report status and scheduling
    - Provide interactive waste location mapping
    - Manage waste collection schedules
    ''')

    # Database Schema
    pdf.add_page()
    pdf.chapter_title('Database Schema')
    migrations = get_migrations()
    for migration in migrations:
        pdf.chapter_body(f"- {migration}")

    # UI Pages
    pdf.add_page()
    pdf.chapter_title('UI Pages')
    views = get_views()
    for view in views:
        pdf.chapter_body(f"- {view}")

    # Models & Relationships
    pdf.add_page()
    pdf.chapter_title('Models')
    models = get_models()
    for model in models:
        pdf.chapter_body(f"- {model}")

    # Technical Requirements
    pdf.add_page()
    pdf.chapter_title('Technical Requirements')
    pdf.chapter_body('''
    Core Requirements:
    - Laravel 10.x
    - PHP 8.2
    - MySQL 8.0
    - Node.js & NPM
    - Composer

    Libraries & Add-ons:
    - Tailwind CSS
    - Alpine.js
    - Spatie Permissions
    - Leaflet.js for maps
    - FPDF for reports
    - Laravel Sanctum for authentication
    ''')

    pdf.output('project_specification.pdf')

if __name__ == '__main__':
    generate_spec()
