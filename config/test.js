/**
 * Created by sle on 8/24/16.
 */
var test = [];
jQuery('.params-table tr').each(function (index) {
    //console.log("\n\n" + $(this).find('td:nth-child(2)').text() + "\n\n");
    if ($(this).find('td:nth-child(2)').text() !== '') {
      var inner = [];
      inner[0] = $(this).find('td').first().text() + ':';
      var type = $(this).find('td:nth-child(2)').text();
      var types = {
        'boolean': 'boolean',
        'number': 'integer',
        'object': 'string',
        'function': 'string',
        'string / HTMLElement': 'string',
        "number or 'auto'": 'string',
        'string': 'string'
      };
      inner[1] = '  type: ' + types[type];
      inner[2] = '  label: ' + "'" + $(this).find('td').first().text() + "'";
      test[index] = inner.join("\n");
    }
  }
);
console.log(test.join("\n"));