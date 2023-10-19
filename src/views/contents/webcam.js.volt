const webCamElement = document.getElementById("webCam");
const canvasElement = document.getElementById("canvas");
const webcam = new Webcam(webCamElement, "user", canvasElement);
webcam.start();

function takeAPicture() {
  let picture = webcam.snap();
  document.getElementById("fotoBase64").value = picture;

  webCamElement.style.display = "none";
  canvasElement.style.display = "block";

  document.getElementById("snap").style.display = "none";
  document.getElementById("undo").style.display = "block";

  document.getElementById("{{ element.getName() }}").disabled = true;
}

function undoTakeAPicture() {
  document.getElementById("fotoBase64").value = "";

  webCamElement.style.display = "block";
  canvasElement.style.display = "none";

  document.getElementById("snap").style.display = "block";
  document.getElementById("undo").style.display = "none";

  document.getElementById("{{ element.getName() }}").disabled = false;
}

function flipCamera() {
  webcam.flip();
}