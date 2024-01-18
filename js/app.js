require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.20.0/min/vs' }});
require(['vs/editor/editor.main'], function() {
    window.editor = monaco.editor.create(document.getElementById('editor-container'), {
        value: '',
       language: 'markdown'
    });
});

async function fetchParser(){
    try{
        const response = await fetch("/parser.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(window.editor.getValue().replace("\n", "\r\n")),
        });
        if (!response.ok) {
            throw new Error(`${response.status} ${response.statusText}`);
        }
        return response.text();
    }catch(error){
        throw (error);
    }
}

function escapeHTML(unsafe){
    return unsafe
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
 }

function renderContent(){
    fetchParser()
    .then((data) => {
        const containerElement = document.getElementById("converted-container");
        const checkedRadioName = document.querySelector('input[name="flexRadio"]:checked').getAttribute("id");
        if(checkedRadioName == "HTMLRadio"){
            const preDOM = document.createElement("pre");
            const codeDOM = document.createElement("code");
            containerElement.innerHTML = "";
            codeDOM.innerHTML = escapeHTML(data);
            containerElement.appendChild(preDOM);
            preDOM.appendChild(codeDOM);
        } else {
            containerElement.innerHTML = data;
        }
    });
};

function downloadContent(){
    const containerElement = document.getElementById("converted-container");
    const checkedRadioName = document.querySelector('input[name="flexRadio"]:checked').getAttribute("id");
    let outputContent;
    let downloadFormat = ".md";

    if(checkedRadioName == "HTMLRadio"){
        outputContent = containerElement.children[0].children[0].innerHTML;
        downloadFormat = ".html";
    } else {
        outputContent = window.editor.getValue();
    }
    const blob = new Blob([outputContent], { type: "octet-stream"});
    const href = URL.createObjectURL(blob);
    const a = Object.assign(document.createElement("a"), {
        href,
        style: "display:none",
        download: "download" + downloadFormat,
    });
    document.body.appendChild(a);
    a.click();
    URL.revokeObjectURL(href);
    a.remove();
};
