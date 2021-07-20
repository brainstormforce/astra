Feature('My First Test');

Scenario('test something', ({ I }) => {
  I.amOnPage('http://astratesting.local/');
  I.see('For Her');
});