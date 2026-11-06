import fs from 'fs';

const manifestPath = 'public/build/manifest.json';
if (fs.existsSync(manifestPath)) {
    const raw = fs.readFileSync(manifestPath, 'utf8');
    const data = JSON.parse(raw);
    const normalized = {};
    for (const [key, value] of Object.entries(data)) {
        normalized[key] = value;
        const cleanKey = key.replace(/^[A-Za-z]:\/.*?resources\//, 'resources/');
        normalized[cleanKey] = value;
    }
    fs.writeFileSync(manifestPath, JSON.stringify(normalized, null, 2));
    console.log('Manifest normalized successfully!');
}
