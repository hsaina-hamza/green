from fpdf import FPDF
from fpdf.enums import XPos, YPos

class ProjectPDF(FPDF):
    def __init__(self):
        super().__init__()
        self.set_auto_page_break(auto=True, margin=15)

    def header(self):
        self.set_font('Helvetica', size=16)
        self.cell(0, 10, 'Green Mastar - Project Specification', 0, 1, 'C')
        self.ln(10)

    def chapter_title(self, title):
        self.set_font('Helvetica', size=14)
        self.cell(0, 10, title, 0, 1, 'L')
        self.ln(5)

    def section(self, title):
        self.set_font('Helvetica', 'B', size=12)
        self.cell(0, 10, title, 0, 1, 'L')

    def content(self, text):
        self.set_font('Helvetica', size=12)
        self.multi_cell(0, 10, text)
        self.ln(5)

def generate_spec():
    pdf = ProjectPDF()
    pdf.add_page()

    # User Role
    pdf.chapter_title('1. User Features')
    pdf.content("""
    Account Management:
    - Create new account
    - Login to existing account
    - Update profile information
    
    Waste Reporting:
    - Report waste locations with images
    - Specify waste type and location
    - Add description and details
    - Track report status
    
    Map Interaction:
    - View interactive waste location map
    - Browse other user reports
    - See garbage truck routes
    
    Communication:
    - Comment on waste reports
    - View garbage collection schedules
    - Receive status updates
    """)

    # Worker Role
    pdf.add_page()
    pdf.chapter_title('2. Worker Features')
    pdf.content("""
    Account Access:
    - Login with admin-created account
    - Update profile information
    
    Report Management:
    - View assigned waste reports
    - Update report status (In Progress, Completed)
    - Add comments and updates
    - Upload completion evidence
    
    Schedule Management:
    - View assigned routes
    - Update garbage truck schedules
    - Mark collections as completed
    
    Communication:
    - Respond to user comments
    - Send status updates
    - Receive new report notifications
    """)

    # Admin Role
    pdf.add_page()
    pdf.chapter_title('3. Admin Features')
    pdf.content("""
    User Management:
    - Create worker accounts
    - Manage user permissions
    - Modify/delete user accounts
    - Monitor user activity
    
    System Administration:
    - Configure waste types
    - Manage collection routes
    - Update truck schedules
    - Set system parameters
    
    Monitoring & Analytics:
    - View site statistics
    - Generate performance reports
    - Track worker efficiency
    - Monitor report resolution times
    
    Content Management:
    - Manage waste categories
    - Update route information
    - Configure notification settings
    - Maintain system data
    """)

    # Technical Details
    pdf.add_page()
    pdf.chapter_title('4. Technical Implementation')
    pdf.content("""
    Database Tables:
    - users: User accounts and roles
    - waste_reports: Report details and status
    - comments: User and worker communications
    - garbage_schedules: Collection routes and times
    - locations: Waste site coordinates
    - waste_types: Categories of waste
    
    Key Features:
    - Role-based access control
    - Real-time notifications
    - Interactive mapping
    - Image upload and storage
    - Report status tracking
    - Analytics dashboard
    
    Technologies:
    - Laravel 10.x Framework
    - MySQL Database
    - Leaflet.js for mapping
    - Tailwind CSS for styling
    - Alpine.js for interactivity
    """)

    pdf.output("project_specification.pdf")

if __name__ == "__main__":
    generate_spec()
