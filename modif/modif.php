<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth.php';
require_login();

$carId = (int) ($_GET['car_id'] ?? 0);
$stmt = get_db()->prepare('SELECT id, car_name, file_name, price, category FROM cars WHERE id = ? LIMIT 1');
$stmt->execute([$carId]);
$car = $stmt->fetch();

if (!$car) {
    redirect_to('/Gallery/gallery.php');
}

$colorOptions = [
    'black' => ['label' => 'Hitam (Standar)', 'price' => 0, 'class' => 'black'],
    'red' => ['label' => 'Merah + Rp 5.000.000', 'price' => 5000000, 'class' => 'red'],
    'white' => ['label' => 'Putih + Rp 3.000.000', 'price' => 3000000, 'class' => 'white'],
    'blue' => ['label' => 'Biru + Rp 4.000.000', 'price' => 4000000, 'class' => 'blue'],
    'gray' => ['label' => 'Abu-abu + Rp 2.000.000', 'price' => 2000000, 'class' => 'gray'],
];
$wheelOptions = [
    '18' => ['label' => 'Velg 18 (Standar)', 'price' => 0],
    '19' => ['label' => 'Velg 19 + Rp 7.000.000', 'price' => 7000000],
    '20' => ['label' => 'Velg 20 + Rp 12.000.000', 'price' => 12000000],
];
$engineOptions = [
    'standard' => ['label' => 'Mesin Standar', 'price' => 0],
    'turbo' => ['label' => 'Turbo + Rp 25.000.000', 'price' => 25000000],
    'v8' => ['label' => 'V8 + Rp 50.000.000', 'price' => 50000000],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Konfigurasi Mobil - Wijaya Cars</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="modif.css?v=20260515-3">
</head>
<body>
<header class="modif-header">
    <a href="../Gallery/gallery.php"><img src="../models/Logo.png" alt="Wijaya Cars Logo"></a>
</header>

<main class="container">
  <div class="title">
    <p class="eyebrow"><?= e($car['category']); ?></p>
    <h1><?= e($car['car_name']); ?></h1>
    <p>Sesuaikan tampilan dan performa sesuai karakter Anda.</p>
  </div>

  <form class="content-wrapper" id="modifForm" method="post" action="../Pembayaran/pembayaran.php">
      <input type="hidden" name="car_id" value="<?= (int) $car['id']; ?>">
      <input type="hidden" name="prepare_checkout" value="1">

      <div class="car-preview" id="preview-container" style="border: none; background: transparent; box-shadow: none; position: relative;">
        <!-- 2D Image Fallback -->
        <img id="car2d" src="../models/<?= e($car['file_name']); ?>" alt="<?= e($car['car_name']); ?>" style="display: none; width: 100%; max-height: 350px; object-fit: contain; filter: drop-shadow(0 20px 30px rgba(0,0,0,0.8));">
        
        <!-- 3D Container -->
        <div id="car3d-container" style="width: 100%; height: 400px; display: none;">
          <div id="loading3d" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #fff;">Memuat Model 3D...</div>
        </div>
      </div>

      <div class="options">
        <section class="box glass-panel">
          <h3>Warna Eksterior</h3>
          <div class="colors" data-option-group="color">
            <?php foreach ($colorOptions as $key => $option): ?>
              <label class="color <?= e($option['class']); ?> <?= $key === 'black' ? 'active-color' : ''; ?>" title="<?= e($option['label']); ?>" tabindex="0" role="button">
                <input type="radio" name="color" value="<?= e($key); ?>" data-label="<?= e($option['label']); ?>" data-price="<?= (int) $option['price']; ?>" <?= $key === 'black' ? 'checked' : ''; ?>>
              </label>
            <?php endforeach; ?>
          </div>
          <div class="price-note" id="infoWarna">Hitam (Standar)</div>
        </section>

        <section class="box glass-panel">
          <h3>Ukuran Velg</h3>
          <div class="wheels" data-option-group="wheel">
            <?php foreach ($wheelOptions as $key => $option): ?>
              <label class="wheel <?= $key == 18 ? 'active' : ''; ?>" tabindex="0" role="button">
                <input type="radio" name="wheel" value="<?= e((string)$key); ?>" data-label="<?= e($option['label']); ?>" data-price="<?= (int) $option['price']; ?>" <?= $key == 18 ? 'checked' : ''; ?>>
                <?= e((string)$key); ?>&quot;
              </label>
            <?php endforeach; ?>
          </div>
          <div class="price-note" id="infoVelg">Velg 18 (Standar)</div>
        </section>

        <section class="box glass-panel">
          <h3>Tipe Mesin</h3>
          <div class="wheels" data-option-group="engine">
            <?php foreach ($engineOptions as $key => $option): ?>
              <label class="wheel <?= $key === 'standard' ? 'active' : ''; ?>" tabindex="0" role="button">
                <input type="radio" name="engine" value="<?= e($key); ?>" data-label="<?= e($option['label']); ?>" data-price="<?= (int) $option['price']; ?>" <?= $key === 'standard' ? 'checked' : ''; ?>>
                <?= e($key === 'standard' ? 'Standar' : strtoupper($key)); ?>
              </label>
            <?php endforeach; ?>
          </div>
          <div class="price-note" id="infoMesin">Mesin Standar</div>
        </section>

        <section class="footer-box glass-panel">
            <div class="total">
              <h4>Estimasi Harga</h4>
              <h2 id="totalHarga"><?= rupiah((int) $car['price']); ?></h2>
            </div>
            <button class="btn btn-next" type="submit">
              <span>Selanjutnya</span>
              <small>Lanjut ke pembayaran</small>
            </button>
        </section>
      </div>
  </form>
</main>

<div class="sticky-checkout">
  <div>
    <span>Estimasi Harga</span>
    <strong id="stickyTotal"><?= rupiah((int) $car['price']); ?></strong>
  </div>
  <button class="sticky-next" id="stickyNext" type="submit" form="modifForm">Selanjutnya</button>
</div>

<script>
const basePrice = <?= (int) $car['price']; ?>;
const form = document.getElementById('modifForm');
const totalEl = document.getElementById('totalHarga');
const stickyTotalEl = document.getElementById('stickyTotal');
const labels = {
  color: document.getElementById('infoWarna'),
  wheel: document.getElementById('infoVelg'),
  engine: document.getElementById('infoMesin')
};

document.querySelectorAll('[data-option-group] input[type="radio"]').forEach(input => {
  input.addEventListener('change', () => syncOption(input));
});

document.querySelectorAll('[data-option-group] label').forEach(option => {
  option.addEventListener('keydown', event => {
    if (event.key === 'Enter' || event.key === ' ') {
      event.preventDefault();
      const input = option.querySelector('input[type="radio"]');
      if (input) {
        input.checked = true;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      }
    }
  });
});

function syncOption(input) {
  const option = input ? input.closest('label') : null;
  const group = input ? input.closest('[data-option-group]') : null;
  if (!input || !option || !group) return;

  group.querySelectorAll('label').forEach(label => label.classList.remove('active', 'active-color'));
  option.classList.add(group.dataset.optionGroup === 'color' ? 'active-color' : 'active');
  if (labels[group.dataset.optionGroup]) {
    labels[group.dataset.optionGroup].textContent = input.dataset.label;
  }
  updateTotal();
}

function updateTotal() {
  const optionTotal = [...document.querySelectorAll('input[type="radio"]:checked')]
    .reduce((sum, input) => sum + Number(input.dataset.price || 0), 0);
  totalEl.textContent = formatRupiah(basePrice + optionTotal);
  stickyTotalEl.textContent = formatRupiah(basePrice + optionTotal);
}

function formatRupiah(value) {
  return 'Rp ' + value.toLocaleString('id-ID');
}

// Custom event dispatch to communicate with Three.js script
function dispatchConfig() {
  const color = document.querySelector('input[name="color"]:checked')?.value || 'black';
  const wheel = document.querySelector('input[name="wheel"]:checked')?.value || '18';
  const engine = document.querySelector('input[name="engine"]:checked')?.value || 'standard';
  
  window.dispatchEvent(new CustomEvent('car-config-changed', {
    detail: { color, wheel, engine }
  }));
}

document.querySelectorAll('input[type="radio"]').forEach(input => {
  input.addEventListener('change', dispatchConfig);
});
</script>

<!-- Import maps polyfill -->
<script async src="https://unpkg.com/es-module-shims@1.8.0/dist/es-module-shims.js"></script>

<script type="importmap">
  {
    "imports": {
      "three": "https://unpkg.com/three@0.160.0/build/three.module.js",
      "three/addons/": "https://unpkg.com/three@0.160.0/examples/jsm/"
    }
  }
</script>

<script type="module">
  import * as THREE from 'three';
  import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
  import { DRACOLoader } from 'three/addons/loaders/DRACOLoader.js';
  import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
  import { RoomEnvironment } from 'three/addons/environments/RoomEnvironment.js';

  const carName = "<?= strtolower(e($car['car_name'])); ?>";
  const car2d = document.getElementById('car2d');
  const car3dContainer = document.getElementById('car3d-container');

  let modelUrlToLoad = '';

  // SISTEM FALLBACK DINAMIS
  if (carName.includes('ferrari')) {
      modelUrlToLoad = 'https://cdn.jsdelivr.net/gh/mrdoob/three.js@r160/examples/models/gltf/ferrari.glb';
  } else if (carName.includes('porsche')) {
      modelUrlToLoad = '../models/porsche_cayenne.glb';
  } else if (carName.includes('mclaren')) {
      modelUrlToLoad = '../models/mclaren_720s.glb';
  } else if (carName.includes('lamborghini') || carName.includes('gallardo')) {
      modelUrlToLoad = '../models/lamborghini_huracan.glb';
  }

  if (modelUrlToLoad !== '') {
      car3dContainer.style.display = 'block';
      init3DViewer(modelUrlToLoad);
  } else {
      car2d.style.display = 'block';
  }

  function init3DViewer(modelUrl) {
      const container = car3dContainer;
      const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
      renderer.setPixelRatio(window.devicePixelRatio);
      renderer.setSize(container.clientWidth, container.clientHeight);
      renderer.toneMapping = THREE.ACESFilmicToneMapping;
      renderer.toneMappingExposure = 1.3;
      // MENGAKTIFKAN SHADOW UNTUK REALISME MAKSIMAL
      renderer.shadowMap.enabled = true;
      renderer.shadowMap.type = THREE.PCFSoftShadowMap;
      container.appendChild(renderer.domElement);

      const scene = new THREE.Scene();

      const camera = new THREE.PerspectiveCamera(40, container.clientWidth / container.clientHeight, 0.1, 100);
      camera.position.set(4, 2, 4);

      const pmremGenerator = new THREE.PMREMGenerator(renderer);
      scene.environment = pmremGenerator.fromScene(new RoomEnvironment(), 0.04).texture;
  
  // PENCAHAYAAN ALA SHOWROOM MOBIL (SHOWROOM LIGHTING)
  const ambientLight = new THREE.AmbientLight(0xffffff, 0.4); 
  scene.add(ambientLight);
  
  const spotLight1 = new THREE.SpotLight(0xffffff, 150); 
  spotLight1.position.set(0, 6, 5);
  spotLight1.angle = Math.PI / 4;
  spotLight1.penumbra = 0.5;
  spotLight1.castShadow = true;
  spotLight1.shadow.mapSize.width = 2048;
  spotLight1.shadow.mapSize.height = 2048;
  spotLight1.shadow.bias = -0.0001;
  scene.add(spotLight1);
  
  const spotLight2 = new THREE.SpotLight(0xffaa55, 80); 
  spotLight2.position.set(-5, 4, -5);
  spotLight2.angle = Math.PI / 4;
  spotLight2.penumbra = 0.5;
  scene.add(spotLight2);
  
  const dirLight = new THREE.DirectionalLight(0xffffff, 2.0); 
  dirLight.position.set(5, 8, 2);
  dirLight.castShadow = true;
  dirLight.shadow.mapSize.width = 2048;
  dirLight.shadow.mapSize.height = 2048;
  dirLight.shadow.bias = -0.0001;
  scene.add(dirLight);

  // LANTAI SHOWROOM UNTUK BAYANGAN
  const floorGeometry = new THREE.PlaneGeometry(20, 20);
  const floorMaterial = new THREE.ShadowMaterial({ opacity: 0.5 });
  const floor = new THREE.Mesh(floorGeometry, floorMaterial);
  floor.rotation.x = -Math.PI / 2;
  floor.position.y = 0; // Akan disesuaikan dengan posisi ban terendah
  floor.receiveShadow = true;
  scene.add(floor);

  const controls = new OrbitControls(camera, renderer.domElement);
  controls.enableDamping = true;
  controls.minDistance = 2;
  controls.maxDistance = 10;
  controls.target.set(0, 0.5, 0);

  let carModel;
  let bodyMaterials = [];
  let wheels = [];
  
  const dracoLoader = new DRACOLoader();
  dracoLoader.setDecoderPath( 'https://unpkg.com/three@0.160.0/examples/jsm/libs/draco/gltf/' );
  
  const loader = new GLTFLoader();
  loader.setDRACOLoader( dracoLoader );
  
  loader.load(
    modelUrl, 
    function (gltf) {
        document.getElementById('loading3d').style.display = 'none';
        carModel = gltf.scene;
        
        // Process model
        carModel.traverse((child) => {
            if (child.isMesh) {
                // Process materials
                if (child.material) {
            // Fix inverted normals or single-sided planes making cars look invisible or patchy from certain angles
            child.material.side = THREE.DoubleSide;
            
            const matName = child.material.name.toLowerCase();
            
            // Detect Body Paint
            if (matName.includes('body') || matName.includes('paint') || matName.includes('color') || matName.includes('regiona') || matName.includes('exterior')) {
                if (!bodyMaterials.includes(child.material)) {
                    bodyMaterials.push(child.material);
                }
            } 
            // Detect Glass
            else if (matName.includes('glass') || matName.includes('window')) {
                child.material = new THREE.MeshPhysicalMaterial({
                    color: 0x000000,
                    metalness: 0.1,
                    roughness: 0.05,
                    transmission: 1.0, // glass effect
                    transparent: true,
                    opacity: 1.0,
                    ior: 1.5,
                    envMapIntensity: 2.0
                });
            }
            // Detect Wheels/Rims
            else if (matName.includes('rim') || matName.includes('alloy') || matName.includes('metal')) {
                child.material.color.setHex(0xaaaaaa);
                child.material.metalness = 0.9;
                child.material.roughness = 0.2;
                child.material.envMapIntensity = 1.5;
            }
            // Detect Tires
            else if (matName.includes('tire') || matName.includes('rubber')) {
                child.material.color.setHex(0x111111);
                child.material.metalness = 0.0;
                child.material.roughness = 0.9;
            }
            // Detect Interior (Seats, Dashboard, Steering Wheel)
            else if (matName.includes('interior') || matName.includes('leather') || matName.includes('seat') || matName.includes('dash') || matName.includes('steering')) {
                child.material.color.setHex(0x2a1a10); // Luxury brown leather
                child.material.metalness = 0.0;
                child.material.roughness = 0.8;
                child.material.clearcoat = 0.1;
            }
            // REMOVED catch-all else block so we don't destroy original materials!
        }
        // Detect wheels for scaling
        const childName = child.name.toLowerCase();
        // HANYA gunakan nama mesh, BUKAN nama material. 
        // Karena part bumper sering menggunakan material karet ban (tire/rubber), 
        // sehingga bumper ikut terdeteksi sebagai ban dan hancur saat diskala.
        if (childName.includes('wheel') || childName.includes('tire') || childName.includes('rim') || childName.includes('alloy')) {
            wheels.push(child);
        }
        // Enable shadows for this part
        child.castShadow = true;
        child.receiveShadow = true;
      }
    });

    // Normalize scale (auto-resize to fit the screen)
    let box = new THREE.Box3().setFromObject(carModel);
    let size = box.getSize(new THREE.Vector3());
    const maxDim = Math.max(size.x, size.y, size.z);
    if (maxDim > 0) {
        const targetSize = 4.5; // ~4.5 meters long
        const scaleFactor = targetSize / maxDim;
        carModel.scale.set(scaleFactor, scaleFactor, scaleFactor);
        carModel.updateMatrixWorld();
        
        // Recalculate bounding box after scaling
        box = new THREE.Box3().setFromObject(carModel);
    }

    // Center model and place on floor
    const center = box.getCenter(new THREE.Vector3());
    carModel.position.x += (carModel.position.x - center.x);
    carModel.position.y += (carModel.position.y - box.min.y); // Set bottom exactly at Y=0
    carModel.position.z += (carModel.position.z - center.z);
    
    // Adjust floor to match the lowest point (Y=0)
    floor.position.y = 0;
    
    scene.add(carModel);
    
    // Trigger initial config
    dispatchConfig();
  }, 
  // onProgress callback
  function (xhr) {
      if (xhr.lengthComputable) {
          const percentComplete = Math.round((xhr.loaded / xhr.total) * 100);
          const loadingText = document.querySelector('#loading3d p');
          if (loadingText) {
              loadingText.innerText = `Memuat Model 3D... ${percentComplete}%`;
          }
      }
  },
  // onError callback
  function (error) {
      console.error('Terjadi kesalahan saat memuat model 3D:', error);
      const loadingText = document.querySelector('#loading3d p');
      if (loadingText) {
          loadingText.innerText = 'Gagal memuat model 3D. Silakan refresh.';
      }
  });

  // Handle Resize
  window.addEventListener('resize', () => {
    camera.aspect = container.clientWidth / container.clientHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(container.clientWidth, container.clientHeight);
  });

  let targetRotationY = 0;
  let currentEngine = 'standard';
  
  window.addEventListener('car-config-changed', (e) => {
     const { color, wheel, engine } = e.detail;
     currentEngine = engine;
     
     // Update Color
     const colors = {
        'black': 0x050505,
        'red': 0x770000,
        'white': 0xdddddd,
        'blue': 0x001155,
        'gray': 0x333333
     };
     bodyMaterials.forEach(mat => {
         if (colors[color] !== undefined) {
             mat.color.setHex(colors[color]);
             if (mat.map) {
                 mat.map = null; // Remove any baked-in color textures (like green) so the pure color shows!
             }
             mat.vertexColors = false; // Disable vertex coloring (this was causing the cyan tint!)
             if (mat.emissive) mat.emissive.setHex(0x000000); // Remove any glowing base color
             mat.needsUpdate = true;
             
             // Shader / Material Enhancements for SUPER REALISTIC Car Paint
             mat.envMapIntensity = 1.5; // Boost reflections
             mat.clearcoat = 1.0;       // Automotive clearcoat layer
             mat.clearcoatRoughness = 0.05; // Make the clearcoat very glossy
             mat.metalness = 0.8;       // Metallic base
             mat.roughness = 0.4;       // Slight roughness under the clearcoat for flake effect
             
             // If white or gray, add a subtle pearlescent / iridescent effect (Bunglon tipis)
             if (color === 'white' || color === 'gray' || color === 'black') {
                 mat.iridescence = 0.3;
                 mat.iridescenceIOR = 1.4;
             } else {
                 mat.iridescence = 0.0;
             }
             
             mat.needsUpdate = true;
         }
     });
     
     // Update Wheels
     const scales = { '18': 1, '19': 1.05, '20': 1.12 };
     const scale = scales[wheel] || 1;
     wheels.forEach(w => {
         // Scale around their local center if possible, otherwise just scale
         w.scale.set(scale, scale, scale);
     });
  });

  const clock = new THREE.Clock();

  function animate() {
    requestAnimationFrame(animate);
    
    const time = clock.getElapsedTime();
    
    // Simulate engine vibration
    if (carModel) {
        if (currentEngine === 'turbo') {
            carModel.position.y = Math.sin(time * 50) * 0.001;
        } else if (currentEngine === 'v8') {
            carModel.position.y = Math.sin(time * 80) * 0.002;
            carModel.rotation.z = Math.sin(time * 60) * 0.0005;
        } else {
            carModel.position.y = 0;
            carModel.rotation.z = 0;
        }
    }

    controls.update();
    renderer.render(scene, camera);
  }
  animate();
}
</script>
</body>
</html>
