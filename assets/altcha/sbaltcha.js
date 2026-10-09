/*
 * SBUIADMIN - ALTCHA
 * La version « external » du widget (compatible avec une CSP stricte)
 * n'embarque aucun worker : on enregistre ici celui de PBKDF2, servi par
 * le site. Chargé en type="module" juste après altcha.min.js.
 */
const sbAltchaWorker = new URL('workers/pbkdf2.js', import.meta.url).href;
for (const algorithm of ['PBKDF2/SHA-256', 'PBKDF2/SHA-384', 'PBKDF2/SHA-512']) {
	globalThis.$altcha.algorithms.set(algorithm, () => new Worker(sbAltchaWorker));
}
