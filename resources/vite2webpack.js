import fs from "fs";
import path from "path";
import md5 from "crypto-js/md5";

/**
 * Vite plugin to convert manifest.json to mix-manifest.json format.
 */
export default function convertToMixManifest(config) {
  return {
    name: "convert-to-mix-manifest", // required, will show up in warnings and errors
    writeBundle() {
      // Specify the path to the generated manifest.json and desired mix-manifest.json

      const addManifestPath = config.addManifest
        ? path.resolve(config.outDir, config.addManifest)
        : null;
      const manifestPath = path.resolve(config.outDir, "manifest.json");
      const mixManifestPath = path.resolve(config.outDir, "mix-manifest.json");

      // Read the Vite manifest.json
      fs.readFile(
        config.addManifest ? addManifestPath : manifestPath,
        (err, data) => {
          if (err) throw err;

          const manifest = JSON.parse(data);
          const mixManifest = {};

          // Convert each entry in the Vite manifest into Mix manifest format
          Object.entries(manifest).forEach(([, value]) => {
            // read file content
            const file = fs.readFileSync(
              path.resolve(config.outDir, value.file)
            );
            const hash = md5(file.toString()).toString();
            // Assuming the value.src and value.file structure, adjust as necessary
            mixManifest[`/${value.file}`] = `/${value.file}?id=${hash}`;
          });

          // Add data to manifest
          if (config.addManifest) {
            fs.readFile(mixManifestPath, (err, data) => {
              if (err) throw err;
              const dataMixManifest = JSON.parse(data);

              writeFile(
                mixManifestPath,
                Object.assign(dataMixManifest, mixManifest)
              );
            });

            return;
          }

          // Write the new mix-manifest.json
          writeFile(mixManifestPath, mixManifest);
        }
      );
    },
  };
}

const writeFile = (mixManifestPath, mixManifest) => {
  fs.writeFile(mixManifestPath, JSON.stringify(mixManifest, null, 2), (err) => {
    if (err) throw err;
    // eslint-disable-next-line no-console
    console.log("mix-manifest.json has been generated.");
  });
};
