jQuery(function($) {
  var container = document.getElementById("admin-sponsored-link");
  var links = JSON.parse(sponsoredLinks.sponsoredLinks);
  
  // alternative of Array.isArray()
  if (Object.prototype.toString.call(links) === "[object Array]") {
    for (var i = 0; i < links.length; i++) {
      container.appendChild(buildSponsoredLink(i, links[i]));
    }
  }

  $(".add-sponsored-link").prop("disabled", container.childElementCount === 2);

  $(document).on("click", ".add-sponsored-link", function(e) {
    var link = {
      thumbnail_attachment_id: "",
      thumbnail_attachment_url: "",
      title: "",
      link: "",
      advertiser: "",
    };
    container.append(buildSponsoredLink(container.childElementCount, link));    
    $(".add-sponsored-link").prop("disabled", container.childElementCount === 2);
  });

  $(document).on("click", ".remove-sponsored-link", function(e) {
    var id = e.target.id.replace("-remove", "");
    document.getElementById(id).remove();
    $(".add-sponsored-link").prop("disabled", container.childElementCount === 2);
  });
});

function buildSponsoredLink(index, link) {
  var prefix = 'smartformat_sponsored_links[' + index + ']';

  var html = "";
  html += '<table class="widefat" id="' + prefix + '" style="width: 99%">';
  html += '<tbody>';

  html += '<tr>';

  html += '<td rowspan="2" style="width: 220px">';
  html += '<div>';
  html += '<img id="' + prefix + '[thumbnail_attachment_id]-preview" src="' + link.thumbnail_attachment_url + '" style="max-height: 100px; max-width: 200px" />';
  html += '</div>';
  html += '<button type="button" id="' + prefix + '[thumbnail_attachment_id]-button" class="button media-select">' + sponsoredLinks.selectThumbnail + '</button>';
  html += '<input type="hidden" name="' + prefix + '[thumbnail_attachment_id]" id="' + prefix + '[thumbnail_attachment_id]" value="' + link.thumbnail_attachment_id + '">';
  html += '</td>';

  html += '<th style="width: 100px; vertical-align: middle">' + sponsoredLinks.title + '</th>';
  html += '<td><input name="' + prefix + '[title]" id="' + prefix + '[title]" value="' + link.title + '" type="text" style="width: 100%"></td>';

  html += '<th style="width: 100px; vertical-align: middle">' + sponsoredLinks.advertiser + '</th>';
  html += '<td><input name="' + prefix + '[advertiser]" id="' + prefix + '[advertiser]" value="' + link.advertiser + '" type="text" style="width: 100%"></td>';

  html += '<td rowspan="2"><button type="button" id="' + prefix + '-remove" class="button remove-sponsored-link">' + sponsoredLinks.remove + '</button></td>';

  html += '</tr>';

  html += '<tr>';

  html += '<th style="width: 100px; vertical-align: middle">' + sponsoredLinks.link + '</th>';
  html += '<td colspan="3"><input name="' + prefix + '[link]" id="' + prefix + '[link]" value="' + link.link + '" type="text" style="width: 100%"></td>';

  html += '</tr>';

  html += '</tbody>';
  html += '</table>';

  var div = document.createElement("div");
  div.innerHTML = html;
  return div.firstChild;
}
