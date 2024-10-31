function downloadCV(fullname, education, skills, experience, contact, description, profileImagePath) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();


    let yPosition = 10;


    doc.setFontSize(16);
    doc.setFont("helvetica", "bold");
    doc.text(`Full Name: ${fullname}`, 10, yPosition);
    yPosition += 10;


    function addSection(title, content) {
        doc.setFontSize(12);
        doc.setFont("helvetica", "bold");
        doc.text(`${title}:`, 10, yPosition);


        doc.setFont("helvetica", "normal");
        const lines = doc.splitTextToSize(content, 180);
        lines.forEach(line => {
            yPosition += 7;
            doc.text(line, 10, yPosition);
        });
        yPosition += 10;
    }

    // Add CV details
    addSection("Education", education);
    addSection("Skills", skills);
    addSection("Experience", experience);
    addSection("Contact", contact);
    addSection("Description", description);

    function addImageToPDF(base64Image) {
        doc.addImage(base64Image, 'JPEG', 10, yPosition, 50, 50);
        yPosition += 60;
        doc.save(`${fullname}_CV.pdf`);
    }

    if (profileImagePath) {
        fetch(profileImagePath)
            .then(response => response.blob())
            .then(blob => {
                const reader = new FileReader();
                reader.onloadend = () => {
                    addImageToPDF(reader.result);
                };
                reader.readAsDataURL(blob);
            })
            .catch(error => {
                console.error('Error loading image:', error);
                doc.save(`${fullname}_CV.pdf`);
            });
    } else {
        doc.save(`${fullname}_CV.pdf`);
    }
}