$.Push = {
  init: function() {
    this.subscribe();
  },
  subscribe: function() {
    const u = new URL(PUBLISH_URL);

    $.each(PUSH_TOPICS, function(key, value) {
      u.searchParams.append("topic", value);
    });

    const es = new EventSource(u);
    es.onmessage = e => {
      data = JSON.parse(e.data);
      $.each(data.targets, function(key, item) {
        if (item === PUSH_TARGET) {
          $.App.notify(
            "bg-" + THEME_NAME,
            "Nouvel utilisateur ajouté",
            TOASTR_POSITION.vertical,
            TOASTR_POSITION.horizontal
          );
        }
      });
    };
  }
};

$(document).ready(function() {
  $.Push.init();
});
