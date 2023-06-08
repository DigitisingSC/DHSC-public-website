/** @type {Partial<ThemeConfig & {extend: Partial<ThemeConfig>}> & {animation: Record<"none" | "spin" | "ping" | "pulse" | "bounce", string>; aria: Record<"checked" | "disabled" | "expanded" | "hidden" | "pressed" | "readonly" | "required" | "selected", string>; aspectRatio: Record<"auto" | "square" | "video", string>; backgroundImage: Record<"none" | "gradient-to-t" | "gradient-to-tr" | "gradient-to-r" | "gradient-to-br" | "gradient-to-b" | "gradient-to-bl" | "gradient-to-l" | "gradient-to-tl", string>; backgroundPosition: Record<"bottom" | "center" | "left" | "left-bottom" | "left-top" | "right" | "right-bottom" | "right-top" | "top", string>; backgroundSize: Record<"auto" | "cover" | "contain", string>; blur: Record<"0" | "none" | "sm" | "DEFAULT" | "md" | "lg" | "xl" | "2xl" | "3xl", string>; borderRadius: Record<"none" | "sm" | "DEFAULT" | "md" | "lg" | "xl" | "2xl" | "3xl" | "full", string>; borderWidth: Record<"0" | "2" | "4" | "8" | "DEFAULT", string>; boxShadow: Record<"sm" | "DEFAULT" | "md" | "lg" | "xl" | "2xl" | "inner" | "none", string>; brightness: Record<"0" | "50" | "75" | "90" | "95" | "100" | "105" | "110" | "125" | "150" | "200", string>; columns: Record<"1" | "2" | "3" | "4" | "5" | "6" | "7" | "8" | "9" | "10" | "11" | "12" | "auto" | "3xs" | "2xs" | "xs" | "sm" | "md" | "lg" | "xl" | "2xl" | "3xl" | "4xl" | "5xl" | "6xl" | "7xl", string>; content: Record<"none", string>; contrast: Record<"0" | "50" | "75" | "100" | "125" | "150" | "200", string>; cursor: Record<"auto" | "default" | "pointer" | "wait" | "text" | "move" | "help" | "not-allowed" | "none" | "context-menu" | "progress" | "cell" | "crosshair" | "vertical-text" | "alias" | "copy" | "no-drop" | "grab" | "grabbing" | "all-scroll" | "col-resize" | "row-resize" | "n-resize" | "e-resize" | "s-resize" | "w-resize" | "ne-resize" | "nw-resize" | "se-resize" | "sw-resize" | "ew-resize" | "ns-resize" | "nesw-resize" | "nwse-resize" | "zoom-in" | "zoom-out", string>; dropShadow: Record<"sm" | "DEFAULT" | "md" | "lg" | "xl" | "2xl" | "none", string | string[]>; flex: Record<"1" | "auto" | "initial" | "none", string>; flexGrow: Record<"0" | "DEFAULT", string>; flexShrink: Record<"0" | "DEFAULT", string>; fontFamily: Record<"sans" | "serif" | "mono", string[]>; fontSize: Record<"xs" | "sm" | "base" | "lg" | "xl" | "2xl" | "3xl" | "4xl" | "5xl" | "6xl" | "7xl" | "8xl" | "9xl", [string, {lineHeight: string}]>; fontWeight: Record<"thin" | "extralight" | "light" | "normal" | "medium" | "semibold" | "bold" | "extrabold" | "black", string>; gradientColorStopPositions: Record<"0%" | "5%" | "10%" | "15%" | "20%" | "25%" | "30%" | "35%" | "40%" | "45%" | "50%" | "55%" | "60%" | "65%" | "70%" | "75%" | "80%" | "85%" | "90%" | "95%" | "100%", string>; grayscale: Record<"0" | "DEFAULT", string>; gridAutoColumns: Record<"auto" | "min" | "max" | "fr", string>; gridAutoRows: Record<"auto" | "min" | "max" | "fr", string>; gridColumn: Record<"auto" | "span-1" | "span-2" | "span-3" | "span-4" | "span-5" | "span-6" | "span-7" | "span-8" | "span-9" | "span-10" | "span-11" | "span-12" | "span-full", string>; gridColumnEnd: Record<"1" | "2" | "3" | "4" | "5" | "6" | "7" | "8" | "9" | "10" | "11" | "12" | "13" | "auto", string>; gridColumnStart: Record<"1" | "2" | "3" | "4" | "5" | "6" | "7" | "8" | "9" | "10" | "11" | "12" | "13" | "auto", string>; gridRow: Record<"auto" | "span-1" | "span-2" | "span-3" | "span-4" | "span-5" | "span-6" | "span-full", string>; gridRowEnd: Record<"1" | "2" | "3" | "4" | "5" | "6" | "7" | "auto", string>; gridRowStart: Record<"1" | "2" | "3" | "4" | "5" | "6" | "7" | "auto", string>; gridTemplateColumns: Record<"1" | "2" | "3" | "4" | "5" | "6" | "7" | "8" | "9" | "10" | "11" | "12" | "none", string>; gridTemplateRows: Record<"1" | "2" | "3" | "4" | "5" | "6" | "none", string>; hueRotate: Record<"0" | "15" | "30" | "60" | "90" | "180", string>; invert: Record<"0" | "DEFAULT", string>; keyframes: Record<"spin" | "ping" | "pulse" | "bounce", Record<string, CSSDeclarationList>>; letterSpacing: Record<"tighter" | "tight" | "normal" | "wide" | "wider" | "widest", string>; lineHeight: Record<"3" | "4" | "5" | "6" | "7" | "8" | "9" | "10" | "none" | "tight" | "snug" | "normal" | "relaxed" | "loose", string>; listStyleType: Record<"none" | "disc" | "decimal", string>; listStyleImage: Record<"none", string>; lineClamp: Record<"1" | "2" | "3" | "4" | "5" | "6", string>; minHeight: Record<"0" | "full" | "screen" | "min" | "max" | "fit", string>; minWidth: Record<"0" | "full" | "min" | "max" | "fit", string>; objectPosition: Record<"bottom" | "center" | "left" | "left-bottom" | "left-top" | "right" | "right-bottom" | "right-top" | "top", string>; opacity: Record<"0" | "5" | "10" | "20" | "25" | "30" | "40" | "50" | "60" | "70" | "75" | "80" | "90" | "95" | "100", string>; order: Record<"1" | "2" | "3" | "4" | "5" | "6" | "7" | "8" | "9" | "10" | "11" | "12" | "first" | "last" | "none", string>; outlineOffset: Record<"0" | "1" | "2" | "4" | "8", string>; outlineWidth: Record<"0" | "1" | "2" | "4" | "8", string>; ringOffsetWidth: Record<"0" | "1" | "2" | "4" | "8", string>; ringWidth: Record<"0" | "1" | "2" | "4" | "8" | "DEFAULT", string>; rotate: Record<"0" | "1" | "2" | "3" | "6" | "12" | "45" | "90" | "180", string>; saturate: Record<"0" | "50" | "100" | "150" | "200", string>; scale: Record<"0" | "50" | "75" | "90" | "95" | "100" | "105" | "110" | "125" | "150", string>; screens: Record<"sm" | "md" | "lg" | "xl" | "2xl", string>; sepia: Record<"0" | "DEFAULT", string>; skew: Record<"0" | "1" | "2" | "3" | "6" | "12", string>; spacing: Record<"0" | "1" | "2" | "3" | "4" | "5" | "6" | "7" | "8" | "9" | "10" | "11" | "12" | "14" | "16" | "20" | "24" | "28" | "32" | "36" | "40" | "44" | "48" | "52" | "56" | "60" | "64" | "72" | "80" | "96" | "px" | "0.5" | "1.5" | "2.5" | "3.5", string>; strokeWidth: Record<"0" | "1" | "2", string>; textDecorationThickness: Record<"0" | "1" | "2" | "4" | "8" | "auto" | "from-font", string>; textUnderlineOffset: Record<"0" | "1" | "2" | "4" | "8" | "auto", string>; transformOrigin: Record<"center" | "top" | "top-right" | "right" | "bottom-right" | "bottom" | "bottom-left" | "left" | "top-left", string>; transitionDelay: Record<"0" | "75" | "100" | "150" | "200" | "300" | "500" | "700" | "1000", string>; transitionDuration: Record<"0" | "75" | "100" | "150" | "200" | "300" | "500" | "700" | "1000" | "DEFAULT", string>; transitionProperty: Record<"none" | "all" | "DEFAULT" | "colors" | "opacity" | "shadow" | "transform", string>; transitionTimingFunction: Record<"DEFAULT" | "linear" | "in" | "out" | "in-out", string>; willChange: Record<"auto" | "scroll" | "contents" | "transform", string>; zIndex: Record<"0" | "10" | "20" | "30" | "40" | "50" | "auto", string>}} */

const defaultTheme = require('tailwindcss/defaultTheme');
const plugin = require('tailwindcss/plugin');
const tailwindColors = require('tailwindcss/colors');
const { rem } = require('polished');
const baseFontSize = 16;

const careColours = {
  green: {
    DEFAULT: '#00a189',
    100: '#00a189',
    80: '#33bda9',
    60: '#66cebe',
    40: '#99D9D0',
    20: '#ccefe9',
  },
  forest: {
    DEFAULT: '#00594c',
    100: '#00594c',
    80: '#337a70',
    60: '#669b94',
    20: '#ccdedb',
  },
  grass: {
    DEFAULT: '#2eb135',
    100: '#2eb135',
    80: '#58c15d',
    60: '#82d086',
    20: '#d5efd7',
  },
  red: {
    DEFAULT: '#BA2109'
  },
  burgundy: {
    DEFAULT: '#8B2346',
  },
  purple: {
    DEFAULT: '#512698',
    20: '#DCD4EA',
  },
  pink: {
    DEFAULT: '#CD66CC',
  },
  orange: {
    DEFAULT: '#E57200',
  },
  yellow: {
    DEFAULT: '#ECAC00',
    60: '#F4CD66',
  },
  lightblue: {
    DEFAULT: '#34B6E4',
  },
}

const careNeutrals = {
  black: {
    DEFAULT: '#101820',
    80: '#002D26',
    60: '#545454',
    40: '#9FA3A6',
    20: '#CFD1D2'
  },
  coolgrey: {
    DEFAULT: '#616265',
    20: '#F8F8F8'
  },
  white: {
    DEFAULT: '#FFFFFF',
  },
}

const systemColours = {
  focus: {
    DEFAULT: '#FDE74C',
  },
  error: {
    DEFAULT: careColours.red,
  },
  success: {
    DEFAULT: careColours.grass,
  },
}

module.exports = {
  content: [
    './stories/**/*.{html,twig,js}',
    './templates/**/*.{html,twig,js}',
  ],
  theme: {
    extend: {
      screens: {
        'tablet': '767px',
        'desktop': '959px',
      },
      colors: {
        transparent: 'transparent',
        current: 'currentColor',
        ...careColours,
        ...careNeutrals,
        ...systemColours
      },
      spacing: {
        '1': rem(5, baseFontSize),
        '2': rem(10, baseFontSize),
        '3': rem(15, baseFontSize),
        '4': rem(20, baseFontSize),
        '5': rem(25, baseFontSize),
        '6': rem(30, baseFontSize),
        '7': rem(40, baseFontSize),
        '8': rem(50, baseFontSize),
        '9': rem(60, baseFontSize),
      },
      fontSize: {
        '2xs':  [rem(14, baseFontSize), {lineHeight: rem(18, baseFontSize)}],
        'xs':   [rem(16, baseFontSize), {lineHeight: rem(22, baseFontSize)}],
        'sm':   [rem(18, baseFontSize), {lineHeight: rem(22, baseFontSize)}],
        'base': [rem(19, baseFontSize), {lineHeight: rem(28, baseFontSize)}],
        'md':   [rem(24, baseFontSize), {lineHeight: rem(30, baseFontSize)}],
        'lg':   [rem(32, baseFontSize), {lineHeight: rem(30, baseFontSize)}],
        'xl':   [rem(36, baseFontSize), {lineHeight: rem(40, baseFontSize)}],
        '2xl':  [rem(48, baseFontSize), {lineHeight: rem(50, baseFontSize)}],
      },
      fontWeight: {
        thin: '100',
        extralight: '200',
        light: '300',
        normal: '400',
        medium: '500',
        semibold: '600',
        bold: '700',
        extrabold: '800',
        black: '900',
      }
    },
  },
  corePlugins: {
    container: false
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/aspect-ratio'),
    function ({ addComponents }) {
      addComponents({
        '.container': {
          maxWidth: '100%',
          paddingLeft: '.5rem',
          paddingRight: '.5rem',
          '@screen tablet': {
            maxWidth: '960px',
            paddingLeft: '1rem',
            paddingRight: '1rem'
          },
          '@screen desktop': {
            maxWidth: '1024px',
            paddingLeft: '1rem',
            paddingRight: '1rem'
          },
        }
      })
    }
  ],
}
