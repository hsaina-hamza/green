from fpdf import FPDF
import os
import glob

def generate_spec():
    pdf = FPDF()
    pdf.add_page()
    pdf.set_font("Helvetica", size=16)
    
    # Title
    pdf.cell(0, 10, "Project Specification", ln=True, align='C')
    pdf.ln(10)
    
    # Database Tables
    pdf.set_font("Helvetica", size=12)
    pdf.cell(0, 10, "Database Tables:", ln=True)
    for file in glob.glob('database/migrations/*.php'):
        with open(file, 'r', encoding='utf-8') as f:
            content = f.read()
            if 'Schema::create' in content:
                table = content.split("Schema::create('")[1].split("'")[0]
                pdf.cell(0, 10, f"- {table}", ln=True)
    
    # Views
    pdf.add_page()
    pdf.cell(0, 10, "Views:", ln=True)
    for root, dirs, files in os.walk('resources/views'):
        for file in files:
            if file.endswith('.blade.php'):
                view = os.path.join(root, file).replace('resources/views/', '')
                pdf.cell(0, 10, f"- {view}", ln=True)
    
    # Models
    pdf.add_page()
    pdf.cell(0, 10, "Models:", ln=True)
    for file in glob.glob('app/Models/*.php'):
        model = os.path.basename(file).replace('.php', '')
        pdf.cell(0, 10, f"- {model}", ln=True)
    
    # Technical Requirements
    pdf.add_page()
    pdf.cell(0, 10, "Technical Requirements:", ln=True)
    requirements = [
        "Laravel 10.x",
        "PHP 8.2",
        "MySQL 8.0",
        "Node.js & NPM",
        "Composer",
        "Tailwind CSS",
        "Alpine.js",
        "Spatie Permissions",
        "Leaflet.js",
        "FPDF",
        "Laravel Sanctum"
    ]
    for req in requirements:
        pdf.cell(0, 10, f"- {req}", ln=True)
    
    pdf.output("project_spec.pdf")

if __name__ == "__main__":
    generate_spec()
