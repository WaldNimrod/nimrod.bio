// WCAG contrast ratio computation for nimrod-bio brand tokens.
// Deterministic, no network. Source tokens: system.css :root (v3.3 LOCKED).
const T = {
  paper: '#f5f3ec', 'paper-2': '#e8e7df', 'paper-3': '#dedccf', line: '#d6d2c2',
  ink: '#1f1e1c', 'ink-soft': '#4a4844', soil: '#5b483a',
  'w-soil-deep': '#3a5220', 'w-soil': '#6a8a3a',
  'w-know-deep': '#9a4f2b', 'w-know': '#c46a3e',
  'w-code-deep': '#1f5e60', 'w-code': '#2d8a8c',
  spark: '#d23a2e', white: '#ffffff',
};
function lin(c){ c/=255; return c<=0.03928 ? c/12.92 : Math.pow((c+0.055)/1.055,2.4); }
function lum(hex){ const r=parseInt(hex.slice(1,3),16),g=parseInt(hex.slice(3,5),16),b=parseInt(hex.slice(5,7),16);
  return 0.2126*lin(r)+0.7152*lin(g)+0.0722*lin(b); }
function ratio(a,b){ const l1=lum(a),l2=lum(b); const hi=Math.max(l1,l2),lo=Math.min(l1,l2); return (hi+0.05)/(lo+0.05); }
function grade(r){ return { AA_normal: r>=4.5, AA_large: r>=3.0, AAA_normal: r>=7.0 }; }

// Foreground colors that appear as TEXT, tested against the backgrounds they sit on.
const backgrounds = ['paper','paper-2','paper-3','white'];
const foregrounds = ['ink','ink-soft','soil','w-soil-deep','w-soil','w-know-deep','w-know','w-code-deep','w-code','spark'];
console.log('FG\\BG'.padEnd(13), backgrounds.map(b=>b.padStart(9)).join(''));
for (const fg of foregrounds){
  let row = fg.padEnd(13);
  for (const bg of backgrounds){
    const r = ratio(T[fg], T[bg]);
    const g = grade(r);
    const flag = g.AA_normal ? 'AA' : (g.AA_large ? 'L ' : 'XX');
    row += `${r.toFixed(2)}${flag}`.padStart(9);
  }
  console.log(row);
}
console.log('\nLegend: AA=passes 4.5:1 normal text · L=large-text only (>=3.0) · XX=fails even large');
