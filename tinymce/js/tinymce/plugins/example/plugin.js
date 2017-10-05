tinymce.PluginManager.add('example', function(editor, url) {
  // Add a button that opens a window
  editor.addButton('example', {
    text: 'My button',
    icon: false,
    onclick: function() {
      // Open window
      editor.windowManager.open({
        title: 'Example plugin',
        body: [
          {type: 'textbox', name: 'title', label: 'Titttle'}
        ],
        onsubmit: function(e) {
          // Insert content when the window form is submitted
          editor.insertContent('Title: ' + e.data.title);
        }
      });
    }
  });

  // Adds a menu item to the tools menu
  editor.addMenuItem('example', {
    text: 'Example plugin',
    context: 'tools',
    onclick: function() {
      // Open window with a specific url
      editor.windowManager.open({
        title: 'TinyMCE site',
        url: 'http://www.tinymce.com',
        width: 800,
        height: 600,
        buttons: [{
          text: 'Close',
          onclick: 'close'
        }]
      });
    }
  });
});
