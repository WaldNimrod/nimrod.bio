// Verify proposed a11y remediation color pairs. AA normal=4.5, AA large=3.0.
function lin(c){c/=255;return c<=0.03928?c/12.92:Math.pow((c+0.055)/1.055,2.4);}
function lum(h){const r=parseInt(h.slice(1,3),16),g=parseInt(h.slice(3,5),16),b=parseInt(h.slice(5,7),16);return 0.2126*lin(r)+0.7152*lin(g)+0.0722*lin(b);}
function ratio(a,b){const l1=lum(a),l2=lum(b),hi=Math.max(l1,l2),lo=Math.min(l1,l2);return (hi+0.05)/(lo+0.05);}
// blend fg rgba over solid bg
function over(fgHex,a,bgHex){const f=[1,3,5].map(i=>parseInt(fgHex.slice(i,i+2),16)),b=[1,3,5].map(i=>parseInt(bgHex.slice(i,i+2),16));const o=f.map((v,i)=>Math.round(v*a+b[i]*(1-a)));return '#'+o.map(v=>v.toString(16).padStart(2,'0')).join('');}
const paper='#f5f3ec', ink='#1f1e1c';
function show(label,fg,bg){const r=ratio(fg,bg);console.log(`${r>=4.5?'PASS':(r>=3?'large':'FAIL')}  ${r.toFixed(2)}  ${label}  (${fg} on ${bg})`);}

console.log('--- P1/P2/P3: CTAs + chips deepened (text on deep world) ---');
show('btn paper/know-deep', paper, '#9a4f2b'); show('btn white/know-deep', '#ffffff', '#9a4f2b');
show('chip white/soil-deep', '#ffffff', '#3a5220'); show('chip white/code-deep', '#ffffff', '#1f5e60');
show('scope paper/soil-deep', paper, '#3a5220'); show('free white/code-deep', '#ffffff', '#1f5e60');
console.log('--- P5: WhatsApp green candidates (white text) ---');
for(const g of ['#1f8a4c','#187a42','#17793f','#15703a']) show('wa '+g, '#ffffff', g);
console.log('--- P6: spark-on-dark candidates (on ink) — need >=4.5 ---');
for(const s of ['#d23a2e','#e0584c','#e8645a','#ef6f64','#f07a6e']) show('spark/ink '+s, s, ink);
console.log('--- P7: spark-on-paper stamp text candidates — need >=4.5 ---');
for(const s of ['#d23a2e','#c8362b','#c22f25','#bd2c22','#b82a20']) show('spark/paper '+s, s, paper);
console.log('--- P8: muted greys ---');
show('.opt ink-soft solid/paper', '#4a4844', paper);
for(const o of [0.78,0.82,0.86,1.0]) show(`.opt ink-soft@${o}/paper`, over('#4a4844',o,paper), paper);
console.log('footer .bottom rgba paper on ink:');
for(const a of [0.45,0.5,0.55,0.6]) show(`footer paper@${a}/ink`, over(paper,a,ink), ink);
console.log('src/gloss rgba paper on ink:');
for(const a of [0.42,0.55,0.6]) show(`src paper@${a}/ink`, over(paper,a,ink), ink);
console.log('--- P9: nav atop scrim (paper text over rgba(18,17,15,a) composited on paper body) ---');
for(const a of [0.5,0.6,0.7,0.72,0.8]) { const bg=over('#12110f',a,paper); show(`atop paper-text scrim@${a}`, paper, bg); }
console.log('atop .wm small candidates (over scrim@.72 bg):');
const scrim72=over('#12110f',0.72,paper);
for(const a of [0.82,1.0]) show(`small paper@${a}/scrim`, over(paper,a,scrim72), scrim72);
