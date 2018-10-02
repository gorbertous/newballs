
let newid = 0;
let tree = [];

export default class Treeview {

    constructor() {
        if ($('.tree-box').length > 0) {
            this.jsonToTree();

            this.addNode();
            this.removeNode();
            this.modifyNode();

            $(document).on('closing', '#modal', function () {
                $(document).off('click', '.add-has-child');
                $(document).off('click', '.remove-child');
                $(document).off('click', '.edit-child');
            });

            $(document).on('mouseover', 'ul.trees ul li', function () {
                $(this).find('.btns').eq(0).css('visibility', 'visible');
            });

            $(document).on('mouseout', 'ul.trees ul li', function () {
                $(this).find('.btns').eq(0).css('visibility', 'hidden');
            });
        }
    }

    /**
     * @returns {string}
     */
    nodeHtml(label) {
        return '<li class="has-child">' +
            '<input type="checkbox" checked><span class="tree-control"></span>' +
            '<label>' + label + '</label>' +
            '<span class="btns">' +
            '<span class="glyphicon glyphicon-pencil edit-child btnm"></span>' +
            '<span class="glyphicon glyphicon-trash remove-child btnm"></span>' +
            '<span class="glyphicon glyphicon-plus add-has-child btnm"></span>' +
            '</span>' +
            '<ul></ul>' +
            '</li>';
    }

    addNode() {
        const $this = this;
        $(document).on('click', '.add-has-child', function (e) {
            e.preventDefault();

            const newNode = $(this).closest('li').find('ul').eq(0);

            $(newNode).append($this.nodeHtml($('.label-why').data('text')));

            $this.treeToJson();
        });
    }

    removeNode() {
        const $this = this;

        $(document).on('click', '.remove-child', function (e) {
            e.preventDefault();

            const removeNode = $(this).closest('li').eq(0);

            krajeeDialog.confirm($('.label-confirm').data('text'), function (result) {
                if (result) {
                    $(removeNode).remove();
                    $this.treeToJson();
                }
            });
        });
    }

    modifyNode() {
        const $this = this;

        $(document).on('click', '.edit-child', function (e) {
            e.preventDefault();

            const node = $(this).closest('li').eq(0).find('label').eq(0);

            const value = $(node).text();

            // add a class to the label we clicked
            $(node).addClass('edit');

            if (value !== undefined) {
                krajeeDialog.prompt({
                    label: $('.label-rename').data('text'),
                    placeholder: value,
                    value: value
                }, function (result) {
                    if (result) {
                        $('.edit').text(result);
                        $this.treeToJson();
                    }
                    // remove every .edit class from the label
                    $('label').removeClass('edit');
                });
            }
        });
    }

    treeToJson() {
        newid = 0;
        tree = [];

        const rootul = $('.trees');

        this.recurseToJson(rootul, newid);

        $('#accidents-fivewhy').val(JSON.stringify(tree));
    }

    recurseToJson(startul, parentid) {
        const childlis = $(startul).children("li");
        const $this = this;

        $.each($(childlis), function (key, childli) {
            const id = newid++;
            const label = $(childli).find('label').eq(0).text();

            if (id !== 0) {
                tree.push({
                    'id': id,
                    'label': label,
                    'parentid': parentid
                });
            }
            const childul = $(childli).children("ul");
            $this.recurseToJson(childul, id);
        });
    }

    jsonToTree() {
        let $accfw = $('#accidents-fivewhy');

        if ($accfw.length > 0) {
            let fivewhyValue = $accfw.val();

            if (fivewhyValue.length > 0) {
                tree = $.parseJSON(fivewhyValue);
                let rootul = $('.trees ul');
                this.recurseToTree(rootul, 0);
            }
        }
    }

    recurseToTree(startul, parentid) {
        let $this = this;
        $.each(tree, function (k, v) {
            if (v.parentid === parentid) {
                let childli = $($this.nodeHtml(v.label));

                $(startul).append(childli);

                let childul = $(childli).children("ul");

                $this.recurseToTree(childul, v.id);
            }
        });
    }

}