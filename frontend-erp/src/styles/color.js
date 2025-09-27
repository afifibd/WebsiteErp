const gradientBgBase = 'bg-gradient-to-tr'

export const gradientBgBlue = `${gradientBgBase} from-blue-400 via-blue-500 to-blue-600`
export const gradientBgLight = `${gradientBgBase} from-blue-100 via-white to-blue-50`
export const gradientBgDark = `${gradientBgBase} from-slate-700 via-slate-900 to-slate-800`

// Background + Text Colors
export const colorsBgLight = {
  white: 'bg-white text-gray-900',
  light: 'bg-gray-50 text-gray-900 dark:bg-slate-900 dark:text-white',
  contrast: 'bg-gray-800 text-white dark:bg-white dark:text-gray-900',
  success: 'bg-emerald-500 border-emerald-500 text-white',
  danger: 'bg-red-500 border-red-500 text-white',
  warning: 'bg-yellow-500 border-yellow-500 text-white',
  info: 'bg-blue-500 border-blue-500 text-white',
  primary: 'bg-blue-600 border-blue-600 text-white',
}

// Text Colors
export const colorsText = {
  default: 'text-gray-800 dark:text-slate-100',
  light: 'text-gray-500 dark:text-slate-400',
  contrast: 'text-gray-900 dark:text-white',
  success: 'text-emerald-600',
  danger: 'text-red-600',
  warning: 'text-yellow-600',
  info: 'text-blue-600',
  primary: 'text-blue-600',
}

// Outline Variants
export const colorsOutline = {
  white: [colorsText.default, 'border-gray-200'],
  light: [colorsText.light, 'border-gray-200'],
  contrast: [colorsText.contrast, 'border-gray-900 dark:border-slate-100'],
  success: [colorsText.success, 'border-emerald-500'],
  danger: [colorsText.danger, 'border-red-500'],
  warning: [colorsText.warning, 'border-yellow-500'],
  info: [colorsText.info, 'border-blue-500'],
  primary: [colorsText.primary, 'border-blue-600'],
}

// Button Color Generator
export const getButtonColor = (color, isOutlined, hasHover) => {
  const colors = {
    bg: {
      white: 'bg-white text-gray-900',
      contrast: 'bg-gray-800 text-white dark:bg-white dark:text-gray-900',
      light: 'bg-gray-50 text-gray-900',
      success: 'bg-emerald-600 text-white',
      danger: 'bg-red-600 text-white',
      warning: 'bg-yellow-600 text-white',
      info: 'bg-blue-600 text-white',
      primary: 'bg-blue-600 text-white',
    },
    bgHover: {
      white: 'hover:bg-gray-100',
      contrast: 'hover:bg-gray-900 hover:dark:bg-slate-100',
      light: 'hover:bg-gray-200',
      success: 'hover:bg-emerald-700',
      danger: 'hover:bg-red-700',
      warning: 'hover:bg-yellow-700',
      info: 'hover:bg-blue-700',
      primary: 'hover:bg-blue-700',
    },
    borders: {
      white: 'border-gray-200',
      contrast: 'border-gray-900 dark:border-slate-100',
      light: 'border-gray-200',
      success: 'border-emerald-600',
      danger: 'border-red-600',
      warning: 'border-yellow-600',
      info: 'border-blue-600',
      primary: 'border-blue-600',
    },
    text: {
      white: 'text-gray-900',
      contrast: 'dark:text-white',
      light: 'text-gray-500',
      success: 'text-emerald-600',
      danger: 'text-red-600',
      warning: 'text-yellow-600',
      info: 'text-blue-600',
      primary: 'text-blue-600',
    },
    outlineHover: {
      white: 'hover:bg-gray-100 hover:text-gray-900',
      contrast: 'hover:bg-gray-800 hover:text-white',
      light: 'hover:bg-gray-100 hover:text-gray-900',
      success: 'hover:bg-emerald-600 hover:text-white',
      danger: 'hover:bg-red-600 hover:text-white',
      warning: 'hover:bg-yellow-600 hover:text-white',
      info: 'hover:bg-blue-600 hover:text-white',
      primary: 'hover:bg-blue-600 hover:text-white',
    },
  }

  if (!colors.bg[color]) {
    return color
  }

  const base = [
    isOutlined ? colors.text[color] : colors.bg[color],
    colors.borders[color],
  ]

  if (hasHover) {
    base.push(isOutlined ? colors.outlineHover[color] : colors.bgHover[color])
  }

  return base
}
