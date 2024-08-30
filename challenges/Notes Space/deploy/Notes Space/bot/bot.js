const puppeteer = require('puppeteer')

const SITE = process.env.SITE || 'http://app.md-notes.space/'
const USERNAME = process.env.USERNAME || 'admin'
const PASSWORD = process.env.PASSWORD || 'admin'

const sleep = async ms => new Promise(resolve => setTimeout(resolve, ms))

let browser = null

const visit = async url => {
    let context = null
    try {
        if (!browser) {
            const args = ['--js-flags=--jitless,--no-expose-wasm', '--disable-gpu', '--disable-dev-shm-usage']
            if (new URL(SITE).protocol === 'http:') {
                args.push(`--unsafely-treat-insecure-origin-as-secure=${SITE}`)
            }
            browser = await puppeteer.launch({
                headless: 'new',
                args
            })
        }

        context = await browser.createBrowserContext()

        // login
        const page = await context.newPage()
        await page.goto(SITE)
        await page.type('#username', USERNAME)
        await page.type('#password', PASSWORD)
        await page.click('button')
        await sleep(500)
        await page.close()

        // visit url
        const page2 = await context.newPage()
        await page2.goto(url)
        await sleep(1000)

        await context.close()
        context = null
    } catch (e) {
        console.log(e)
    } finally {
        if (context) await context.close()
    }
}

module.exports = visit

if (require.main === module) {
    visit('http://example.com')
}
