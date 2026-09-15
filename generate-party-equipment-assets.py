import json, time, urllib.request
from pathlib import Path

API='http://127.0.0.1:8188'; MODEL='sd_xl_base_1.0.safetensors'
assets=[
 ('11-microphone', 'close editorial photograph of two wireless microphones and a gooseneck lectern microphone neatly arranged on a refined banquet stage in a Japanese wedding venue, soft warm spotlight, elegant dark wood and sage green accents, realistic hospitality photography, wide horizontal composition, no readable text, no logos'),
 ('12-audio-stage', 'wide editorial photograph of a small event stage in a Japanese wedding banquet hall, podium, speakers, microphones, subtle floral decoration, clean professional PA setup for a company party or community ceremony, warm elegant lighting, realistic interior photography, no readable text, no logos'),
 ('13-seating-layout', 'wide editorial photograph of a banquet hall prepared for a seated dinner party, neatly arranged round tables, white chairs, table numbers without readable text, clear aisle for speeches, microphones and small stage visible in the background, refined wedding venue, realistic interior photography, no readable text, no logos'),
]
def req(path,data=None):
    body=None if data is None else json.dumps(data).encode(); r=urllib.request.Request(API+path,data=body,headers={'Content-Type':'application/json'})
    with urllib.request.urlopen(r,timeout=30) as h:return json.load(h)
def graph(prompt,seed,prefix):
    return {'1':{'class_type':'CheckpointLoaderSimple','inputs':{'ckpt_name':MODEL}},'2':{'class_type':'CLIPTextEncode','inputs':{'clip':['1',1],'text':prompt+', photorealistic, 16:9 wide composition'}},'3':{'class_type':'CLIPTextEncode','inputs':{'clip':['1',1],'text':'readable text, letters, logo, watermark, malformed hands, distorted microphone, blurry, dark, illustration, cartoon, CGI'}},'4':{'class_type':'EmptyLatentImage','inputs':{'width':1344,'height':768,'batch_size':1}},'5':{'class_type':'KSampler','inputs':{'model':['1',0],'positive':['2',0],'negative':['3',0],'latent_image':['4',0],'seed':seed,'steps':28,'cfg':6.5,'sampler_name':'dpmpp_2m','scheduler':'karras','denoise':1.0}},'6':{'class_type':'VAEDecode','inputs':{'samples':['5',0],'vae':['1',2]}},'7':{'class_type':'SaveImage','inputs':{'images':['6',0],'filename_prefix':'gb_iizuka_party_'+prefix}}}
results=[]
for n,(prefix,prompt) in enumerate(assets):
    pid=req('/prompt',{'prompt':graph(prompt,9164000+n*113,prefix),'client_id':'gb-iizuka-party-lp'})['prompt_id']; print('Submitted',prefix,pid,flush=True); start=time.monotonic()
    while True:
        h=req('/history/'+pid).get(pid)
        if h and h.get('status',{}).get('status_str')=='error': raise RuntimeError(h)
        if h and h.get('status',{}).get('completed'):
            im=h.get('outputs',{}).get('7',{}).get('images',[{}])[0]; results.append({'id':prefix,'prompt':prompt,'seed':9164000+n*113,'image':im}); print('Completed',im.get('filename'),flush=True); break
        if time.monotonic()-start>1800: raise TimeoutError(prefix)
        time.sleep(2)
Path('party-equipment-assets-manifest.json').write_text(json.dumps({'model':MODEL,'assets':results},ensure_ascii=False,indent=2),encoding='utf-8')
