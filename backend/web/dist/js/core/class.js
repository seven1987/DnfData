/**
 * JavaScript Inheritance Class
 *
 * 实现了继承和定义对象，初始化自动调用 ctor() 构造函数，自带一个简单的 clone() 拷贝函数
 * 每个 Class 都带有一个唯一ID : _pid，每个 new 出来的对象也带一个唯一ID : _uid (可以用于相同 Class 的不同对象)
 *
 * @author : zhenda.li
 * @date : 2016.12.15
 */

/**
 * @name ClassManager
 */
var ClassManager = {
    pid: (0 | (Math.random() * 998)), // 每个 Class 的唯一ID
    uid: (0 | (Math.random() * 998)), // 每个 New 出来的对象唯一ID

    getPID: function () {
        return this.pid++;
    },

    getUID: function () {
        return this.uid++;
    }
};

(function () {
    var fnTest = /\b_super\b/;

    this.Class = function () {
    };

    /**
     * Create a new Class that inherits from this Class
     * @static
     * @param {object} prop
     * @return {function}
     */
    Class.extend = function (prop) {
        var _super = this.prototype;
        var prototype = Object.create(_super);
        var desc = {writable: true, enumerable: false, configurable: true};

        function Class() {
            this._uid = ClassManager.getUID();
            if (this.ctor) {
                this.ctor.apply(this, arguments);
            }
        }

        desc.value = ClassManager.getPID();
        Object.defineProperty(prototype, '_pid', desc);

        // desc.value = [];
        // Object.defineProperty(prototype, '_data', desc);

        for (var name in prop) {
            var isFunc = isFunction(prop[name]);
            var override = isFunction(_super[name]);
            var hasSuperCall = fnTest.test(prop[name]);

            if (isFunc && override && hasSuperCall) {
                desc.value = (function (name, fn) {
                    return function () {
                        var tmp = this._super;
                        this._super = _super[name];
                        var ret = fn.apply(this, arguments);
                        this._super = tmp;
                        return ret;
                    };
                })(name, prop[name]);
                Object.defineProperty(prototype, name, desc);
            } else {
                prototype[name] = prop[name];
            }

            // if (!isFunc) {
            //     prototype._data[name] = prototype[name];
            // }
        }

        Class.prototype = prototype;

        desc.value = Class;
        Object.defineProperty(Class.prototype, 'constructor', desc);

        /**
         * 快捷设置属性方法
         * e.g : obj.attr({ x : 1, y : 2 });
         */
        Class.prototype.attr = function (attrs) {
            for (var key in attrs) {
                if (!isUndefined(this[key]))
                    this[key] = attrs[key];
            }
        };

        /**
         * 每个对象自带拷贝方法
         * @return Class
         */
        Class.prototype.clone = function () {
            var newObj = (this.constructor) ? new this.constructor : {};
            for (var name in this) {
                var copy = this[name];
                if ((typeof copy) === "Class") {
                    newObj[name] = copy.clone();
                } else {
                    newObj[name] = copy;
                }
            }
            return newObj;
        };

        /**
         * 获取该对象的所有属性集合(排除函数和对象)
         * 如果包含了对象呢？未处理？
         * @return Object
         */
        Class.prototype.data = function () {
            var data = {};
            for (var name in this) {
                if (name == '_uid' || isFunction(this[name]) || isObject(this[name])) {
                    continue;
                }
                data[name] = this[name];
            }
            return data;
        };

        Class.extend = arguments.callee;

        return Class;
    };
})();