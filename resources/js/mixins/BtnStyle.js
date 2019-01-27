import classNames from "classnames"
import PropTypes from "../helpers/PropTypes";

export default {
  props: {
    theme: {
      type: String,
      default: "default",
      validator: PropTypes.oneOf(['default', 'primary', 'success', 'danger'])
    },
    btnClass: {
      type: [String, Object],
      default: ""
    },
    size: {
      type: String,
      default: ""
    },
    disabled: {
      type: Boolean,
      default: false
    },
    loading: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    btnStyle() {
      let style = "";
      switch (this.theme) {
        case 'default':
          style = 'bg-white border border-grey-light hover:bg-grey-lightest text-grey-darkest no-underline'
          break
        case 'primary':
          style = 'bg-blue hover:bg-blue-dark text-white no-underline'
          break
        case 'success':
          style = 'bg-green-theme hover:bg-green text-white no-underline'
          break
        case 'danger':
          style = 'bg-red hover:bg-red-dark text-white no-underline'
          break
      }
      return classNames([style, 'font-bold', 'rounded-full', 'py-2', 'px-4' ,
        this.btnClass,  'flex items-center', this.disabled ? 'cursor-not-allowed' : '']);
    }
  },
}