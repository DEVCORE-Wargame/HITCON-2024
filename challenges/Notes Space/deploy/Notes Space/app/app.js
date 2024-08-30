function render(markdown) {
    const md = window.markdownit();
    const html = md.render(markdown);
    return DOMPurify.sanitize(html);
}

