const form = document.getElementById("upload-form");
const resultDiv = document.getElementById("result");

form.addEventListener("submit", function(event) {
    event.preventDefault();

    const formData = new FormData(form);

    fetch("/upload", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                resultDiv.innerHTML = `<p id="error">${data.error}</p>`;
            } else {
                resultDiv.innerHTML = `<p>${data.result}</p>`;
            }
        })
        .catch(error => {
            console.error(error);
            resultDiv.innerHTML = `<p id="error">An error occurred while detecting the plant disease.</p>`;
        });
});