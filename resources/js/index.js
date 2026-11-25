import { Passkeys } from '@laravel/passkeys'

window.FilamentPasskeys = {
    async register(name) {
        if (! name?.trim()) {
            return
        }

        return Passkeys.register({ name })
    },

    async login(redirect, remember = false) {
        const response = await Passkeys.verify({ remember })

        window.location.href = redirect || response?.redirect || '/'
    },
}
