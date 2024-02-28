let speakBtn = document.getElementById("speakBtn");
let shareBtn = document.getElementById("shareBtn");
let backBtn = document.getElementById("backBtn");

function disabledMessage(btn) {
  btn.disabled = true;
  btn.classList.add("btn-danger");
  btn.querySelector(".material-symbols-rounded").innerText = "warning";
  btn.querySelector(".text").innerText = "Unsupported Feature";
}

let isSpeaking = false;
if ("speechSynthesis" in window) {
  speakBtn.addEventListener("click", () => {
    if (!isSpeaking) {
      const text = document.querySelector(".article .content").innerText;
      const synth = window.speechSynthesis;
      const utterance = new SpeechSynthesisUtterance(text);
      const voices = synth.getVoices();
      utterance.voice = voices[0];
      synth.speak(utterance);
      isSpeaking = true;
      speakBtn.querySelector(".material-symbols-rounded").innerText = "close";
      speakBtn.querySelector(".text").innerText = "Stop";
      speakBtn.classList.add("btn-danger");
    } else {
      window.speechSynthesis.cancel();
      isSpeaking = false;
      speakBtn.querySelector(".material-symbols-rounded").innerText = "campaign";
      speakBtn.querySelector(".text").innerText = "Speak";
      speakBtn.classList.remove("btn-danger");
    }
  });
} else {
  disabledMessage(speakBtn);
}

if (navigator.share) {
  shareBtn.addEventListener("click", async () => {
    let url = shareBtn.getAttribute("data-url") == "null" ? location.href : shareBtn.getAttribute("data-url");
    try {
      const shareData = {
        title: document.title,
        text: "Check out my article!",
        url: url,
      };
      await navigator.share(shareData);
    } catch (error) {
      console.error(error);
      shareBtn.querySelector(".material-symbols-rounded").innerText = "Error sharing";
      shareBtn.querySelector(".text").innerText = "error";
      shareBtn.classList.add("btn-danger");
    }
  });
} else {
  disabledMessage(shareBtn);
}

backBtn.addEventListener("click", () => {
  window.history.back();
});
