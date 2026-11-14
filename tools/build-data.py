"""Build the runtime catalogue from official X-Wines Slim CSVs (stdlib only).

Usage: python tools/build-data.py wines.csv ratings.csv original-grapes.csv
Raw ratings/user IDs are never included in the deployed website.
"""
import ast
import csv
import hashlib
import json
import sys
from collections import defaultdict
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
KEYS = ['acidity', 'body', 'sweetness', 'citrus', 'apple', 'tropical', 'stone',
        'melon', 'pear', 'nuts', 'redfruit', 'flowers', 'honey', 'herbs']


def build(wines_path, ratings_path, grapes_path):
    totals = defaultdict(lambda: [0, 0])
    with open(ratings_path, encoding='utf-8-sig', newline='') as stream:
        for row in csv.DictReader(stream):
            rating = float(row['Rating'])
            if not 1 <= rating <= 5:
                raise ValueError('Rating out of range')
            item = totals[int(row['WineID'])]
            item[0] += rating
            item[1] += 1
    body = {'Very light-bodied': 0, 'Light-bodied': 2.5, 'Medium-bodied': 5,
            'Full-bodied': 7.5, 'Very full-bodied': 10}
    acidity = {'Low': 0, 'Medium': 5, 'High': 10}
    wines = []
    with open(wines_path, encoding='utf-8-sig', newline='') as stream:
        for row in csv.DictReader(stream):
            total, count = totals[int(row['WineID'])]
            wines.append(dict(id=int(row['WineID']), name=row['WineName'], type=row['Type'],
                              grapes=ast.literal_eval(row['Grapes']), country=row['Country'],
                              region=row['RegionName'], winery=row['WineryName'],
                              abv=float(row['ABV']), body=body[row['Body']],
                              acidity=acidity[row['Acidity']],
                              rating=round(total / count, 3) if count else None, ratings=count))
    grapes = []
    with open(grapes_path, encoding='utf-8-sig', newline='') as stream:
        for row in csv.reader(stream):
            if row[0] == 'Druivensoort':
                continue
            grapes.append(dict(name=row[0], profile=dict(zip(KEYS, [float(v)*2 for v in row[1:]]))))
    meta = dict(source='X-Wines Slim', wines=len(wines), ratings=sum(w['ratings'] for w in wines),
                grape_varieties=len({g for w in wines for g in w['grapes']}),
                grape_profiles=len(grapes), snapshot='2026-11-14',
                hashes={Path(p).name: hashlib.sha256(Path(p).read_bytes()).hexdigest()
                        for p in (wines_path, ratings_path, grapes_path)})
    for filename, content in [('wines.json', wines), ('grapes.json', grapes), ('metadata.json', meta)]:
        (ROOT / 'data' / filename).write_text(json.dumps(content, ensure_ascii=False, separators=(',', ':'))+'\n', encoding='utf-8')
    print(json.dumps(meta, indent=2))


if __name__ == '__main__':
    build(*sys.argv[1:])
