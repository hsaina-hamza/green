from fpdf import FPDF
import os
import glob
import re

def generate_spec():
    pdf = FPDF()
    pdf.add_page()
    pdf.set_font("Helvetica", size=16)
    
    # Title
    pdf.cell(0, 10, "Project Specification", 0, 1, 'C')
    pdf.ln(10)
    
    # Database Tables
    pdf.set_font("Helvetica", size=12)
    pdf.cell(0, 10, "Database Tables:", 0, 1)
    
    # List all migration files
    migrations = glob.glob('database/migrations/*.php')
    for file in migrations:
        pdf.cell(0, 10, f"- {os.path.basename(file)}", 0, 1)
    
    # Views
    pdf.add_page()
    pdf.cell(0, 10, "Views:", 0, 1)
    
    # List all blade files
    views = []
    for root, dirs, files in os.walk('resources/views'):
        for file in files:
            if file.endswith('.blade.php'):
                views.append(os.path.join(root, file).replace('resources/views/', ''))
    
    for view in sorted(views):
        pdf.cell(0, 10, f"- {view}", 0, 1)
    
    # Models
    pdf.add_page()
    pdf.cell(0, 10, "Models:", 0, 1)
    
    # List all model files
    models = glob.glob('app/Models/*.php')
    for file in models:
        pdf.cell(0, 10, f"- {os.path.basename(file).replace('.php', '')}", 0, 1)
    
    pdf.output("project_spec.pdf")

if __name__ == "__main__":
    generate_spec()
