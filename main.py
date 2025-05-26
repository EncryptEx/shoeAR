from fastapi import FastAPI, HTTPException, Depends, Form
from pydantic import BaseModel
from sqlalchemy import create_engine, Column, Integer, String
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker, Session
from fastapi import HTTPException, UploadFile, File
from PIL import Image
from fastapi.responses import FileResponse
from fastapi.middleware.cors import CORSMiddleware

DATABASE_URL = "sqlite:///./shoes.db"

engine = create_engine(DATABASE_URL, connect_args={"check_same_thread": False})
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)
Base = declarative_base()

app = FastAPI()
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Adjust this in production!
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

class Shoe(Base):
    __tablename__ = "shoes"
    id = Column(Integer, primary_key=True, index=True)
    marker = Column(Integer, unique=True, index=True)
    imgpath = Column(String, nullable=True)
    
Base.metadata.create_all(bind=engine)

class MarkerRequest(BaseModel):
    number: int

def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()

@app.post("/add_shoe/")
def add_shoe(
    marker_number: int = Form(...), 
    file: UploadFile = File(), 
    db: Session = Depends(get_db)
):
    try:
        if marker_number < 0 or marker_number > 63:
            raise HTTPException(status_code=400, detail="Marker number must be between 0 and 63.")
        existing_shoe = db.query(Shoe).filter(Shoe.marker == marker_number).first()
        if existing_shoe:
            raise HTTPException(status_code=400, detail=f"Shoe with marker {marker_number} already exists.")
        
        newshoeimgpath = f"shoes/shoe-{marker_number}.png"
        
        try:        
            im = Image.open(file.file)
            if im.mode in ("RGBA", "P"): 
                im = im.convert("RGB")
            im.save(newshoeimgpath, 'PNG', quality=50)
        
        finally:
            file.file.close()
            im.close()
        
        shoe = Shoe(marker=marker_number, imgpath=newshoeimgpath)
        
        db.add(shoe)
        db.commit()
        db.refresh(shoe)
        return {"message": f"Shoe with marker {marker_number} added successfully."}
    except Exception as e:
        db.rollback()
        raise HTTPException(status_code=500, detail=str(e))

    
@app.get("/get_shoe/{marker_number}")
def get_shoe(marker_number: int, db: Session = Depends(get_db)):
        shoe = db.query(Shoe).filter(Shoe.marker == marker_number).first()
        if not shoe:
            raise HTTPException(status_code=404, detail="Shoe not found.")
        if not shoe.imgpath:
            raise HTTPException(status_code=404, detail="Image path not set for this shoe.")
        try:
            return FileResponse(
                path=f"shoes/shoe-{marker_number}.png",
                media_type="image/png",
                filename=f"shoe-{marker_number}.png",
                headers={"Content-Disposition": "inline"}
            )
        except Exception as e:
            raise HTTPException(status_code=500, detail=str(e))


@app.get("/get_shoes/")
def get_shoes(db: Session = Depends(get_db)):
    try:
        shoes = db.query(Shoe).all()
        return {"shoes": [{"marker": shoe.marker} for shoe in shoes]}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
