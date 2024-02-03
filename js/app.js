require.config({ paths: { 'vs': './node_modules/monaco-editor/min/vs' }});
require(['vs/editor/editor.main'], function() {
    window.editor = monaco.editor.create(document.getElementById('editor-container'), {
        value: '@startuml\nparticipant Bob\nactor Alice\nBob -> Alice : hello\nAlice -> Bob : Is it ok?\n@enduml',
       language: 'plaintext'
    });
    monaco.editor.setTheme('vs-dark');
});

async function fetchPlantUML(format, operation){
    try{
        const response = await fetch("/envokePlantUML.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                textBody: window.editor.getValue(),
                outputFormat: format,
                operationName: operation,
            }),
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
    let fileFormat = document.querySelector('input[name="flexRadio"]:checked').getAttribute("id");
    if(fileFormat == 'txt') fileFormat = 'atxt';
    fetchPlantUML(fileFormat, 'render')
    .then((data) => {
        console.log(data);
        const containerElement = document.getElementById("converted-container");
        if(containerElement.innerHTML != data){
            containerElement.innerHTML = data;
        }
    });
};

function downloadContent(){
    let fileFormat = document.querySelector('input[name="flexRadio"]:checked').getAttribute("id");
    if(fileFormat == 'txt') fileFormat = 'atxt';

    fetchPlantUML(fileFormat, 'download')
    .then((data) => {
        let blob = new Blob([data], { type: "octet-stream"});
        const href = URL.createObjectURL(blob);
        const a = Object.assign(document.createElement("a"), {
            href,
            style: "display:none",
            download: "diagram." + fileFormat,
        });
        document.body.appendChild(a);
        a.click();
        URL.revokeObjectURL(href);
        a.remove();
    });
};
