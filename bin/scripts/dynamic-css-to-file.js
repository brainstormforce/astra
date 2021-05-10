const puppeteer = require('puppeteer');
const fs = require('fs');

const url = process.argv[2];
const stylesheetId = process.argv[3];

async function getCssFromUrl( url, stylesheetId ) {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.goto(url);
    const inlineCss = await page.$eval(stylesheetId, el => el.innerText)
    await browser.close()

    fs.writeFileSync('assets/dynamic-css.css', inlineCss.replace(/(^[ \t]*\n)/gm, ""));
}

getCssFromUrl(url,stylesheetId);
