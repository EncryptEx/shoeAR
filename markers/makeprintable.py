from fpdf import FPDF
import os
from PIL import Image
from io import BytesIO

def create_pdf_with_markers(marker_count=64, markers_per_row=2, markers_per_col=2):
    pdf = FPDF()
    pdf.set_auto_page_break(auto=True, margin=15)
    # Calculate marker size and margin dynamically to fit the page
    page_width = 210  # A4 width in mm
    page_height = 297  # A4 height in mm
    margin = 10  # Minimum margin in mm

    available_width = page_width - (margin * (markers_per_row + 1))
    available_height = page_height - (margin * (markers_per_col + 1))
    marker_size = min(available_width / markers_per_row, available_height / markers_per_col)

    markers_per_page = markers_per_row * markers_per_col

    for i in range(marker_count):
        if i % markers_per_page == 0:
            pdf.add_page()

        # Generate the marker image
        image_data = get_marker_image(i)
        if not image_data:
            print(f"Marker {i} not found, skipping.")
            continue
        image = Image.open(BytesIO(image_data))
        
        # Save the image temporarily
        temp_image_path = f"temp_marker_{i}.png"
        image.save(temp_image_path)

        # Calculate position
        idx_on_page = i % markers_per_page
        row = idx_on_page // markers_per_row
        col = idx_on_page % markers_per_row
        x = margin + col * (marker_size + margin)
        y = margin + row * (marker_size + margin)

        # Add the image to the PDF
        pdf.image(temp_image_path, x=x, y=y, w=marker_size, h=marker_size)

        # Remove the temporary image file
        os.remove(temp_image_path)

    # Save the PDF to a file
    pdf_file_path = "markers.pdf"
    pdf.output(pdf_file_path)
    print(f"PDF with markers saved to {pdf_file_path}")

def get_marker_image(number):
    file_path = f"markers/marker_{number}.png"
    if os.path.exists(file_path):
        with open(file_path, "rb") as file:
            return file.read()
    else:
        raise FileNotFoundError(f"Marker image {file_path} not found.")

if __name__ == "__main__":
    create_pdf_with_markers(marker_count=64, markers_per_row=3, markers_per_col=4)
    print("PDF creation complete.")
