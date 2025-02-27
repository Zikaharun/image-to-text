document.getElementById("uploadForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let formData = new FormData(this);

    fetch("process.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log("Debug Response: ", data);

        if (data.error) {
            document.getElementById("result").innerHTML = `<p class="text-danger">${data.error}</p>`;
        } else {
            document.getElementById('result').innerHTML = `
            <h2 class="text-center text-dark">Hasil Penilaian</h2>
            <p>Deskripsi: <span>${data.description}</span></p>
            <p>Skor Skena: <span>${data.skena_score}/100</span></p>`;
        }
    })
    .catch(error => console.log("Error:", error));
});

document.getElementById("imageInput").addEventListener("change", function(event) {
    let file = event.target.files[0];

    if (file) {
        let reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("imagePreview").src = e.target.result;
            document.getElementById("imagePreview").style.display = "block";
        };
        reader.readAsDataURL(file);
    }
});