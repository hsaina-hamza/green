from fpdf import FPDF

def generate_spec():
    pdf = FPDF()
    pdf.add_page()
    
    # Title
    pdf.set_font("Helvetica", size=16)
    pdf.cell(0, 10, "Project Specification", new_x="LMARGIN", new_y="NEXT", align="C")
    pdf.ln(10)
    
    # Content
    pdf.set_font("Helvetica", size=12)
    
    sections = [
        ("1. Project Overview", """
        Municipal Waste Management System
        - Waste location tracking and reporting
        - Interactive mapping interface
        - Role-based access control
        - Real-time status updates
        - Automated scheduling system"""),
        
        ("2. Core Features", """
        - User Management
        - Waste Reports
        - Location Tracking
        - Schedule Management
        - Notifications
        - Analytics Dashboard"""),
        
        ("3. Technical Stack", """
        - Laravel 10.x
        - PHP 8.2
        - MySQL 8.0
        - Node.js & NPM
        - Tailwind CSS
        - Alpine.js"""),
        
        ("4. Key Components", """
        - Authentication System
        - Role Management
        - Report Generation
        - Map Integration
        - Email Notifications
        - Mobile Responsiveness""")
    ]
    
    for title, content in sections:
        pdf.set_font("Helvetica", size=14)
        pdf.cell(0, 10, title, new_x="LMARGIN", new_y="NEXT")
        pdf.ln(5)
        
        pdf.set_font("Helvetica", size=12)
        for line in content.split('\n'):
            pdf.cell(0, 10, line.strip(), new_x="LMARGIN", new_y="NEXT")
        pdf.ln(10)
    
    pdf.output("project_specification.pdf")

if __name__ == "__main__":
    generate_spec()
