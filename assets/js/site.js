'use strict';
const root = document.documentElement;
const themeButton = document.querySelector('#theme-toggle');
function setTheme(theme) {
  root.dataset.bsTheme = theme;
  themeButton.textContent = theme === 'dark' ? 'Licht thema' : 'Donker thema';
  themeButton.setAttribute('aria-label', `Schakel naar ${theme === 'dark' ? 'licht' : 'donker'} thema`);
}
try { setTheme(localStorage.getItem('wine-theme') === 'light' ? 'light' : 'dark'); } catch (_) { /* Storage is optional. */ }
themeButton.addEventListener('click', () => {
  const theme = root.dataset.bsTheme === 'dark' ? 'light' : 'dark';
  setTheme(theme);
  try { localStorage.setItem('wine-theme', theme); } catch (_) { /* No preference persistence. */ }
});
const dimensions = [...document.querySelectorAll('[data-dimension]')];
function updateProgress() {
  let complete = 0;
  for (const card of dimensions) {
    const skipped = card.querySelector('.skip-dimension').checked;
    const inputs = [...card.querySelectorAll('input[type="number"]')];
    card.classList.toggle('is-skipped', skipped);
    for (const input of inputs) input.disabled = skipped;
    if (skipped || inputs.every(input => input.value !== '' && input.validity.valid)) complete++;
  }
  document.querySelector('#taste-progress').value = complete;
  document.querySelector('#progress-label').textContent = `${complete} van ${dimensions.length} dimensies afgerond`;
}
document.querySelector('#taste-form').addEventListener('input', updateProgress);
document.querySelector('#taste-form').addEventListener('change', updateProgress);
updateProgress();
document.querySelector('#print-result')?.addEventListener('click', () => window.print());
const message = document.querySelector('#errors') || document.querySelector('#resultaten');
if (message) {
  message.focus({preventScroll: true});
  message.scrollIntoView({block: 'start'});
}
