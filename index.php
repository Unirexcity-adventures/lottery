<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>UNIREXCITY RANDOM PICKER</title>
  <script src="js/snapshot.js"/></script>
  <script src="js/unirexcity-web3-tools.min.js"/></script>
  <link rel="stylesheet" href="style/index.css">
</head>

<body id="body">
  <div class="container">
    <header>
      <img src="https://cdn.shopify.com/s/files/1/0598/5137/4768/files/Frame_1_1_2ed6777f-78af-4d88-924d-422699ed6394_1500x.png?v=1632167701" class="header__image">
    </header>
    <h1>COMIC BOOK NUMBER 2</h1>
    <button data-pick-an-unirex-button class="unirex-button">pick a winner</button>
    <main id="main" class="align-left"></main>
  </div>
</body>


<script>

  let cluster = "https://api.mainnet-beta.solana.com";

  let settings = {
    buttonSelector : '[data-pick-an-unirex-button]'
  }

  async function getMeta(mint) {
    let accountInfo = await unirexcityWeb3Tools.getMetadataNFT(mint, cluster);
    if (!accountInfo) return 0;
    let metadataUri = accountInfo?.data?.uri?.trim();
    let metadata = await fetch(encodeURI(metadataUri).split('%00')[0]).then(async res => await res.json())

    return metadata;
  }

  function selectToken () {
      // array of owners
      let ownersKeys      = Object.keys(snapshot);

      // exclude marketplaces
      let realOwners       = ownersKeys.filter(key => !["3D49QorJyNaL4rcpiynbuS3pRH4Y7EXEM6v6ZGaqfFGK", "GUfCR9mK6azb9vcpsxgXyj7XRPAKJd4KMHTTVvtncGgp", "4pUQS4Jo2dsfWzt3VgHXy3H6RYnEDd11oWPiaM2rdAPw"].includes(key));

      // random owner
      let random            = Math.floor(Math.random() * realOwners.length)
      let selectedOwner = realOwners[random];

      // random token
      let winnerOwner   = snapshot[selectedOwner];
      let randomMint    = Math.floor(Math.random() * winnerOwner.mints.length)
      let NFT = winnerOwner.mints[randomMint];

      return NFT;
  }

  let main = document.getElementById("main");
  var index = 1;
  let max_nft_to_select = 15;

  document.querySelector(settings.buttonSelector).addEventListener('click', async function(e) {
      if (index > max_nft_to_select) {
        document.querySelector(settings.buttonSelector).disabled = true
        return;
      }
      let winnerToken = selectToken();


      let tokenInfo = await getMeta(winnerToken);
      console.log(tokenInfo);
      let image = tokenInfo.image;
      let name = tokenInfo.name;

      let div = document.createElement("div");
      div.className = "unirex-winner";

      div.innerHTML = '<div class="unirex-winner__number">' + index + '</div>' +
      '<img class="unirex-winner__image" src="' + image + '" />' +
      '<div class="unirex-winner__info">' +
        '<div class="unirex-winner__name heading z-h2">' + name + '</div>' +
        '<p class="unirex-winner__adress">' + winnerToken + '</p>' +
      '</div>';

      main.prepend(div);
      index++;
  });

</script>
