# use https://au.gmented.com/app/marker/marker.php?genImage=&marker_type=matrix&gen_single_number=5&marker_size=80&marker_image_resolution=72&ecc_type=none&border_size=0.25&border_is_white=false&border_quiet_zone=true&barcode_dimensions=3

# but iterate over 1 - 63


import requests
import os
import json

    
def generate_marker(number):
    url = "https://au.gmented.com/app/marker/marker.php"
    params = {
        "genImage": "",
        "marker_type": "matrix",
        "gen_single_number": number,
        "marker_size": 80,
        "marker_image_resolution": 72,
        "ecc_type": "none",
        "border_size": 0.25,
        "border_is_white": "false",
        "border_quiet_zone": "true",
        "barcode_dimensions": 3
    }
    
    response = requests.get(url, params=params)
    
    if response.status_code == 200:
        return response.content
    else:
        raise Exception(f"Error generating marker: {response.status_code}")
    
def save_marker_image(number, image_data):
    directory = "markers"
    if not os.path.exists(directory):
        os.makedirs(directory)
    
    file_path = os.path.join(directory, f"marker_{number}.png")
    
    with open(file_path, "wb") as file:
        file.write(image_data)
    
    print(f"Marker {number} saved to {file_path}")

def main():
    for i in range(0, 1):
        try:
            image_data = generate_marker(i)
            save_marker_image(i, image_data)
        except Exception as e:
            print(f"Failed to generate marker {i}: {e}")
        
if __name__ == "__main__":
    main()