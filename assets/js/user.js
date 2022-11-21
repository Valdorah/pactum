import './app'
import '../css/user.sass'

const btnRemoveAccount = document.getElementById('remove-account')

if(btnRemoveAccount){
  btnRemoveAccount.addEventListener('click', async e => {
    let res = await fetch(`/user/delete-account/${btnRemoveAccount.dataset.id}`)
    let json = await res.json()
    if(json){
      window.location = 'http://localhost:8081/logout'
    }
  });
}