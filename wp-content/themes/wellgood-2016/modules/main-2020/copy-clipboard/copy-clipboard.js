import select from 'select-dom';

module.exports = el => {
    el.onclick = ev => {
        ev.preventDefault();
        const textarea = document.createElement('textarea');
        textarea.value = el.getAttribute('data-copy');
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);  
        var messageEl = select(el.getAttribute('data-message-target'), el);
        var previousContent = messageEl.textContent;
        messageEl.textContent = 'Copied!';
        setTimeout(()=>{
            messageEl.textContent = previousContent
        }, 5000)
        
    }
   
}