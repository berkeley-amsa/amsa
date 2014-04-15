(function () {
    var createLine = function (data) {
        data = data || { 'image': '', 'link': '', 'desc': '' };
        var line = new Element('div').addClass('JVListSlide-line');
        line.els = {};
        line.els.head = new Element('div').addClass('JVListSlide-head').inject(line);
        line.els.attrs = new Element('div').addClass('JVListSlide-attrs').inject(line);
        line.els.btnDel = new Element('span').set({
            'class': 'btnDelete',
            events: {
                click: function () {
                    line.destroy();
                }
            }
        }).inject(line);
        line.els.image = new Element('label').addClass('JVListSlide-atribute').inject(line.els.attrs);
        line.els.image.input = new Element('input', { 'value': data.image }).inject(line.els.image.set('html', '<span>Image</span>'));
        line.els.link = new Element('label').addClass('JVListSlide-atribute').inject(line.els.attrs);
        line.els.link.input = new Element('input', { 'value': data.link }).inject(line.els.link.set('html', '<span>Redirect</span>'));
        line.els.desc = new Element('label').addClass('JVListSlide-atribute').inject(line.els.attrs);
        line.els.desc.input = new Element('textarea', { 'value': data.desc }).inject(line.els.desc.set('html', '<span>Description</span>'));
        line.data = function () {
            return {
                'image': line.els.image.input.value,
                'link': line.els.link.input.value,
                'desc': line.els.desc.input.value
            }
        }
        return line;
    }
    window.FieldAddImageList = new Class({
        initialize: function (s) {
            var This = this;
            this.panel = new Element('div').set({ 'class': 'images' }).inject(s.getNext());
            this.addBtn = new Element('span').set({
                class:'btnAdd',
                events: {
                    click: function () {
                        This.add();
                    }
                }
            }).inject(s.getNext());
            this.sort = new Sortables(this.panel, {
                clone: true,
                revert: true,
                opacity: 0.7,
                handle: '.JVListSlide-head',
                onStart: function (e, c) {
                    This.minimize();      
                    return false;
                },
                onSort: function (e, c) {
                    var lastTo = This.panel.getElement('.to');
                    lastTo && lastTo.removeClass('to');
                    e.addClass('to');
                    console.log('sort');
                },
                onComplete: function (e, c) {
                    This.restore();
                    This.resetIndex();
                }
            });
            var submit = Joomla.submitbutton;
            Joomla.submitbutton = function () {
                s.value = JSON.stringify(This.data());
                submit.apply(window, arguments);
            }
            var data = JSON.decode(s.value);
            data.each(function (data) {
                This.add(data);
            });
        },
        add: function (data) {
            var line = createLine(data).inject(this.panel);
            this.resetIndex();
            this.sort.addItems(line);
        },
        resetIndex: function () {
            var i = 1;
            this.panel.getChildren().each(function (el) {
                if (el.data) {
                    el.els.head.set('text', 'Slide ' + i);
                    i++;
                }
            });
        },
        minimize: function () {
            this.panel.getChildren().each(function (el) {
                el.addClass('hidechild');
            });
        },
        restore: function () {
            this.panel.getChildren().each(function (el) {
                el.removeClass('hidechild');
            });
        },
        data: function () {
            var data = [];
            this.panel.getChildren().each(function (el) {
                data.push(el.data());
            });
            return data;
        }
    });
})();
