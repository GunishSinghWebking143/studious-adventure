const $=id=>document.getElementById(id);
let mode='login';
async function api(url,opts={}){const r=await fetch(url,{...opts,headers:{'Content-Type':'application/json',...(opts.headers||{})}});const d=await r.json();if(!r.ok)throw Error(d.error||'Request failed');return d}
async function search(){const q=$('q').value.trim(),source=$('source').value;if(!q)return;$('message').textContent='Searching…';$('results').innerHTML='';$('player').innerHTML='';
try{const d=await api('../api/music.php?'+new URLSearchParams({q,source}));$('message').textContent=d.items.length+' result(s)';$('results').innerHTML=d.items.map((x,i)=>`<article class="card"><img src="${x.image||''}" alt=""><div><b>${x.title}</b><p>${x.artist}</p><small>${source}</small></div>${x.external?`<a target="_blank" rel="noopener" href="${x.url}">Open</a>`:`<button data-i="${i}">Play</button>`}</article>`).join('');window.items=d.items}catch(e){$('message').textContent=e.message}}
$('search').onclick=search;$('q').addEventListener('keydown',e=>{if(e.key==='Enter')search()});
$('results').onclick=e=>{const b=e.target.closest('button[data-i]');if(!b)return;const x=window.items[+b.dataset.i];$('player').innerHTML=x.source==='spotify'?`<iframe src="${x.embed}" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"></iframe>`:`<iframe src="${x.embed}?autoplay=1" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>`};
function openAuth(){ $('modal').hidden=false }
$('account').onclick=async()=>{try{const d=await api('../api/auth.php?action=me');if(d.user){if(confirm('Log out?')){await api('../api/auth.php?action=logout',{method:'POST'});$('account').textContent='Log in'}}else openAuth()}catch{openAuth()}};
$('close').onclick=()=>$('modal').hidden=true;
$('switch').onclick=e=>{e.preventDefault();mode=mode==='login'?'signup':'login';$('authTitle').textContent=mode==='login'?'Log in':'Create account';$('username').style.display=mode==='signup'?'block':'none';$('switch').textContent=mode==='login'?'Create account':'Back to login'};
$('username').style.display='none';
$('auth').onsubmit=async e=>{e.preventDefault();const data={email:$('email').value,password:$('password').value,username:$('username').value};try{await api('../api/auth.php?action='+mode,{method:'POST',body:JSON.stringify(data)});$('modal').hidden=true;$('account').textContent='My account'}catch(err){$('error').textContent=err.message}};
