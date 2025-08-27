import { ref, watchEffect } from 'vue';

const THEME_KEY = 'theme';

export function useDarkMode() {
  const isDark = ref(false);

  // Initial preference
  const stored = localStorage.getItem(THEME_KEY);
  if (stored) {
    isDark.value = stored === 'dark';
  } else {
    // Use theme system if there is no persistence
    isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
  }

  // Sync with <html> class and localStorage
  watchEffect(() => {
    const html = document.documentElement;
    if (isDark.value) {
      html.classList.add('dark');
      localStorage.setItem(THEME_KEY, 'dark');
    } else {
      html.classList.remove('dark');
      localStorage.setItem(THEME_KEY, 'light');
    }
  });

  return { isDark };
}
