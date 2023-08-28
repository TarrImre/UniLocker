// Add a global error event listener early on in the page load, to help ensure that browsers
// which don't support specific functionality still end up displaying a meaningful message.
window.addEventListener('error', function (error) {
    if (ChromeSamples && ChromeSamples.setStatus) {
        console.error(error);
        ChromeSamples.setStatus(error.message + ' (Nem támogatott böngésző)');
        error.preventDefault();
    }
});


var ChromeSamples = {
    log: function () {
        var line = Array.prototype.slice.call(arguments).map(function (argument) {
            return typeof argument === 'string' ? argument : JSON.stringify(argument);
        }).join(' ');

        document.querySelector('#log').textContent += line + '\n';
    },

    clearLog: function () {
        document.querySelector('#log').textContent = '';
    },

    setStatus: function (status) {
        document.querySelector('#status').textContent = status;
    },

    setContent: function (newContent) {
        var content = document.querySelector('#content');
        while (content.hasChildNodes()) {
            content.removeChild(content.lastChild);
        }
        content.appendChild(newContent);
    }
};

log = ChromeSamples.log;

if (!("NDEFReader" in window))
    ChromeSamples.setStatus("NFC nem elérhető!");

scanButton.addEventListener("click", async () => {
    const circles = document.querySelectorAll('.circle');
    const randomtext = document.getElementById("randomtext");
    //scanButton.style.display = "none";

    circles.forEach(circle => {
        circle.style.backgroundColor = "#1dbef9";
    });

    //log("User clicked scan button");

    try {
        const ndef = new NDEFReader();
        await ndef.scan();
        document.getElementById("Unipass").value = ("ERROR");
        randomtext.innerHTML = "Beolvasás...";
        
        ndef.addEventListener("readingerror", () => {
            document.getElementById("Unipass").value = ("ERROR");
            randomtext.innerHTML = "Ezt a kártyát nem tudom beolvasni!";
            circles.forEach(circle => {
                circle.style.backgroundColor = "#f57a7a";
            });
        });

        ndef.addEventListener("reading", ({
            message,
            serialNumber
        }) => {
            const parts = serialNumber.split(':');
            const numParts = parts.length;
            const numZerosToAdd = 7 - numParts;
            if (numZerosToAdd > 0) {
                for (let i = 0; i < numZerosToAdd; i++) {
                    parts.push('0');
                }
            }
            const formattedSerialNumber = parts.join('');
            document.getElementById("Unipass").value = formattedSerialNumber;
            randomtext.innerHTML = "Kész!";
           
            //make the circles style background color to green
            circles.forEach(circle => {
                circle.style.backgroundColor = "#44cc66";
            });
            //log(`> Records: (${message.records.length})`);
        });
    } catch (error) {
        document.getElementById("Unipass").value = ("ERROR" /*+error*/);
        //randomtext.innerHTML = "Hiba " /*+error*/;
    }
});
