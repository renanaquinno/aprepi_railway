import { chromium } from 'playwright';

const usuarios = [
  { email: 'renan@gmail.com', senha: '12345678', nome: 'admin' },
  { email: 'ferreira.camila@example.net', senha: '12345678', nome: 'voluntario_adm' },
  { email: 'aramires@example.net', senha: '12345678', nome: 'voluntario_ext' },
  { email: 'samara45@example.net', senha: '12345678', nome: 'doador' },
  { email: 'amelia.barros@example.net', senha: '12345678', nome: 'socio' },
];

const baseUrl = 'http://localhost';

const rotasPublicas = [
  { url: `${baseUrl}/`, nome: 'home' },
  { url: `${baseUrl}/sobre-nos`, nome: 'sobre-nos' },
  { url: `${baseUrl}/contato`, nome: 'contato' },
  { url: `${baseUrl}/voluntariado`, nome: 'voluntariado_create' },
];

const rotasAuth = [
  { url: `${baseUrl}/profile`, nome: 'profile_edit' },
];

const rotasAdminResource = [
  'usuarios',
  'doacoes',
  'eventos',
  'cestas',
];

const rotasAdminEspeciais = [
  { url: `${baseUrl}/admin/voluntarios`, nome: 'admin_voluntarios_index' },
  { url: `${baseUrl}/admin/dashboard`, nome: 'admin_dashboard' },

  { url: `${baseUrl}/admin/eventos/relatorio/pdf`, nome: 'admin_eventos_relatorio_pdf' },
  { url: `${baseUrl}/admin/cestas/relatorio/pdf`, nome: 'admin_cestas_relatorio_pdf' },
];

const rotasVolAdmExtras = [
  { url: `${baseUrl}/usuarios/relatorio/pdf`, nome: 'usuarios_relatorio_pdf' },
  { url: `${baseUrl}/doacoes/relatorio/pdf`, nome: 'doacoes_relatorio_pdf' },
  { url: `${baseUrl}/eventos/relatorio/pdf`, nome: 'eventos_relatorio_pdf' },
  { url: `${baseUrl}/cestas/relatorio/pdf`, nome: 'cestas_relatorio_pdf' },
  { url: `${baseUrl}/dashboard`, nome: 'voladm_dashboard' },
];

function geraRotasResource(basePath) {
  return [
    { url: `${basePath}`, nome: 'index' },
    { url: `${basePath}/3`, nome: 'show' },
    { url: `${basePath}/3/edit`, nome: 'edit' },
  ];
}

(async () => {
  for (const user of usuarios) {
    const browser = await chromium.launch({ headless: true });
    const page = await browser.newPage();

    try {
      console.log(`\n🔐 Logando como ${user.nome} (${user.email})`);
      await page.goto(`${baseUrl}/login`);
      await page.fill('input[name="email"]', user.email);
      await page.fill('input[name="password"]', user.senha);
      await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle' }),
        page.click('button[type="submit"]'),
      ]);
      console.log(`✅ Login feito: ${user.nome}`);

      // Sempre acessa rotas públicas
      for (const rota of rotasPublicas) {
        try {
          await page.goto(rota.url);
          await page.waitForLoadState('networkidle');
          await page.screenshot({ path: `prints/${user.nome}_${rota.nome}.png`, fullPage: true });
          console.log(`✅ Print pública: ${rota.nome}`);
        } catch (e) {
          console.log(`❌ Erro rota pública ${rota.url}:`, e.message);
        }
      }

      // Usuário admin acessa rotas admin
      if (user.nome === 'admin') {
        // Admin rotas recursos
        for (const resource of rotasAdminResource) {
          for (const rota of geraRotasResource(`${baseUrl}/admin/${resource}`)) {
            try {
              await page.goto(rota.url);
              await page.waitForLoadState('networkidle');
              await page.screenshot({ path: `prints/${user.nome}_admin_${resource}_${rota.nome}.png`, fullPage: true });
              console.log(`✅ Admin print: ${resource} ${rota.nome}`);
            } catch (e) {
              console.log(`❌ Erro admin rota ${rota.url}:`, e.message);
            }
          }
        }
        // Admin rotas extras
        for (const rota of rotasAdminEspeciais) {
          try {
            await page.goto(rota.url);
            await page.waitForLoadState('networkidle');
            await page.screenshot({ path: `prints/${user.nome}_${rota.nome}.png`, fullPage: true });
            console.log(`✅ Admin print extra: ${rota.nome}`);
          } catch (e) {
            console.log(`❌ Erro admin rota extra ${rota.url}:`, e.message);
          }
        }
      }

      // Voluntário adm acessa suas rotas
      if (user.nome === 'voluntario_adm') {
        // Recursos no prefixo raiz (não admin)
        for (const resource of rotasAdminResource) {
          for (const rota of geraRotasResource(`${baseUrl}/${resource}`)) {
            try {
              await page.goto(rota.url);
              await page.waitForLoadState('networkidle');
              await page.screenshot({ path: `prints/${user.nome}_${resource}_${rota.nome}.png`, fullPage: true });
              console.log(`✅ VolAdm print: ${resource} ${rota.nome}`);
            } catch (e) {
              console.log(`❌ Erro voladm rota ${rota.url}:`, e.message);
            }
          }
        }
        for (const rota of rotasVolAdmExtras) {
          try {
            await page.goto(rota.url);
            await page.waitForLoadState('networkidle');
            await page.screenshot({ path: `prints/${user.nome}_${rota.nome}.png`, fullPage: true });
            console.log(`✅ VolAdm print extra: ${rota.nome}`);
          } catch (e) {
            console.log(`❌ Erro voladm rota extra ${rota.url}:`, e.message);
          }
        }
      }

      // Usuários restantes (voluntario_ext, socio, doador) só acessam home e perfil e dashboard se possível
      if (['voluntario_ext', 'socio', 'doador'].includes(user.nome)) {
        // Profile (autenticado)
        try {
          await page.goto(`${baseUrl}/profile`);
          await page.waitForLoadState('networkidle');
          await page.screenshot({ path: `prints/${user.nome}_profile_edit.png`, fullPage: true });
          console.log(`✅ Print profile: ${user.nome}`);
        } catch {}

        // Dashboard (se tiver acesso)
        try {
          await page.goto(`${baseUrl}/dashboard`);
          await page.waitForLoadState('networkidle');
          await page.screenshot({ path: `prints/${user.nome}_dashboard.png`, fullPage: true });
          console.log(`✅ Print dashboard: ${user.nome}`);
        } catch {}
      }

    } catch (err) {
      console.log(`❌ Falha no login ou execução para ${user.nome}:`, err.message);
    }

    await browser.close();
  }

  console.log('\n🎉 Prints concluídos!');
})();
