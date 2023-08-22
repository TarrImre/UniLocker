function myLocker(id,NeptunCode,UniPassCode) {
    const domLock = document.querySelector(".lock");
    const toggleButton = document.getElementById("toggleButton");
    let closed = true;
    let animationInProgress = false;

    toggleButton.addEventListener("click", () => {
        if (!animationInProgress) {

            closed = !closed;
            animationInProgress = true;
            domLock.classList.toggle("closed", closed);

            let anim = closed ?
                "LinearShake ease-in-out 280ms, 360ms AngularShake ease-in-out 280ms" :
                "LinearShake ease-in-out 280ms";

            domLock.style.animation = "none";
            setTimeout(() => {
                domLock.style.animation = anim;
                if (!closed) {
                    var url =`https://api.toxy.hu/update.php?id=${id}&status=on&NeptunCode=${NeptunCode}&UniPassCode=${UniPassCode}`;
                    $.getJSON(url, function (data) {
                        console.log(data);
                    });
                    document.getElementById("lockerMSG").innerHTML = `Nyitva!`;
                    setTimeout(() => {

                        closed = true;
                        domLock.classList.add("closed");
                        domLock.style.animation = "none";
                        animationInProgress = false;

                    }, 3000); // 3 másodperc után visszazárás
                    setTimeout(() => {
                        var url = `https://api.toxy.hu/update.php?id=${id}&status=off&NeptunCode=${NeptunCode}&UniPassCode=${UniPassCode}`;
                        $.getJSON(url, function (data) {
                            console.log(data);
                        });
                        document.getElementById("lockerMSG").innerHTML = `Kérlek zárd be a szekrényt!`;
                    }, 3000); // 3 másodperc után visszazárás
                }
                else {
                    animationInProgress = false;
                }
            }, 0);
        }
    });
}
