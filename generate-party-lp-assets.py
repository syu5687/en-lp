import json, time, urllib.request
from pathlib import Path

API = 'http://127.0.0.1:8188'
OUT = Path('party-lp-assets-manifest.json')
MODEL = 'sd_xl_base_1.0.safetensors'

assets = [
 ('02-banquet-scene', 'wide editorial photograph of a Japanese banquet venue in Iizuka Fukuoka, 40 guests enjoying a welcoming party at elegant round tables, natural candid atmosphere, refined wedding venue interior, warm sage green and cream palette, realistic hospitality photography, no readable text, no logos'),
 ('03-buffet-service', 'wide editorial photograph of a professional banquet staff member serving beautifully plated dishes from a buffet station to guests, Japanese wedding venue party, attentive hospitality, clean elegant setup, warm natural lighting, realistic food photography, no readable text, no logos'),
 ('04-plated-course', 'close wide food photograph of a refined Japanese-western fusion banquet course meal on a beautifully set table, seasonal Kyushu ingredients, several small dishes, wine and sparkling water, wedding venue hospitality, natural warm light, realistic high-end food photography, no text, no logos'),
 ('05-kaiseki', 'top-down and angled editorial food photograph of a refined Japanese kaiseki banquet meal, seasonal colorful dishes in elegant ceramic plates, premium but approachable, suitable for a family memorial meal or formal celebration, warm neutral table setting, realistic photography, no text, no logos'),
 ('06-bento', 'beautiful Japanese celebratory bento box for a banquet venue, black lacquer-style box opened to reveal colorful Japanese-western dishes, clean premium presentation, suitable for a memorial service or family celebration, soft studio light, realistic food photography, no text, no logos'),
 ('07-drink-plan', 'still life photograph of banquet drink service, beer bottle, wine glasses, Japanese sake carafe, non-alcoholic sparkling drink, elegant linen table setting, warm evening reception atmosphere, realistic hospitality photography, no readable text, no logos'),
 ('08-shuttle-bus', 'professional clean white microbus waiting outside an elegant regional Japanese wedding and banquet venue, guests arriving in the background, welcoming daylight, subtle greenery, realistic commercial photography, no readable text, no logos'),
 ('09-projector-meeting', 'wide photograph of a bright banquet hall arranged for a corporate seminar or community meeting, projector screen, wireless microphones, classroom seating, clean professional wedding venue, realistic interior photography, no readable text, no logos'),
 ('10-venue-exterior', 'wide exterior photograph of a tasteful regional Japanese wedding venue in Iizuka Fukuoka, clear entrance, landscaped approach, parking area, inviting early evening light, suitable for a banquet landing page, realistic architecture photography, no readable text, no logos'),
]

def req(path, data=None):
    body = None if data is None else json.dumps(data).encode()
    r = urllib.request.Request(API + path, data=body, headers={'Content-Type':'application/json'})
    with urllib.request.urlopen(r, timeout=30) as h:
        return json.load(h)

def graph(prompt, seed, prefix):
    return {
      '1': {'class_type':'CheckpointLoaderSimple','inputs':{'ckpt_name':MODEL}},
      '2': {'class_type':'CLIPTextEncode','inputs':{'clip':['1',1],'text':prompt + ', photorealistic, 16:9 wide composition'}},
      '3': {'class_type':'CLIPTextEncode','inputs':{'clip':['1',1],'text':'people close-up, distorted faces, malformed hands, deformed objects, blurry, dark underexposed, oversaturated, illustration, cartoon, CGI, text, letters, logo, watermark'}},
      '4': {'class_type':'EmptyLatentImage','inputs':{'width':1344,'height':768,'batch_size':1}},
      '5': {'class_type':'KSampler','inputs':{'model':['1',0],'positive':['2',0],'negative':['3',0],'latent_image':['4',0],'seed':seed,'steps':28,'cfg':6.5,'sampler_name':'dpmpp_2m','scheduler':'karras','denoise':1.0}},
      '6': {'class_type':'VAEDecode','inputs':{'samples':['5',0],'vae':['1',2]}},
      '7': {'class_type':'SaveImage','inputs':{'images':['6',0],'filename_prefix':'gb_iizuka_party_' + prefix}}
    }

results = []
for i, (prefix, prompt) in enumerate(assets, start=2):
    submitted = req('/prompt', {'prompt': graph(prompt, 9152026+i*101, prefix), 'client_id':'gb-iizuka-party-lp'})
    pid = submitted['prompt_id']
    print(f'Submitted {prefix}: {pid}', flush=True)
    started = time.monotonic()
    while True:
        hist = req('/history/' + pid).get(pid)
        if hist and hist.get('status',{}).get('status_str') == 'error':
            raise RuntimeError(f'{prefix}: {hist.get("status")}')
        if hist and hist.get('status',{}).get('completed'):
            image = hist.get('outputs',{}).get('7',{}).get('images',[{}])[0]
            results.append({'id':prefix,'prompt':prompt,'seed':9152026+i*101,'image':image,'seconds':round(time.monotonic()-started,1)})
            print(f'Completed {prefix}: {image.get("filename")}', flush=True)
            break
        if time.monotonic() - started > 1800:
            raise TimeoutError(prefix)
        time.sleep(2)

OUT.write_text(json.dumps({'model':MODEL,'workflow':'SDXL Base 1.0, 1344x768, 28 steps, dpmpp_2m/karras','assets':results}, ensure_ascii=False, indent=2), encoding='utf-8')
print('Manifest:', OUT.resolve())
