function parsePo(poContent) {
    let translations = {};
    let lines = poContent.split("\n");
    let msgid = null;
    let msgstr = null;

    lines.forEach(line => {
        if (line.startsWith('msgid')) {
            msgid = line.replace('msgid "', '').replace('"', '');
        } else if (line.startsWith('msgstr')) {
            msgstr = line.replace('msgstr "', '').replace('"', '');
            if (msgid) {
                translations[msgid] = msgstr;
            }
        }
    })
       return translations;
}

function _(msgid) {
let translation = prismatranslation[msgid]; 
return translation ? translation : msgid; 
}
