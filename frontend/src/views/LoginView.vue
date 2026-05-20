<template>
  <div class="login-root">
    <div class="login-panel">
      <!-- Header strip -->
      <div class="login-header">
        <div class="login-logo">TF</div>
        <div>
          <div class="login-title">TASKFLOW</div>
          <div class="login-subtitle">{{ isLogin ? 'SIGN IN TO CONTINUE' : 'CREATE AN ACCOUNT' }}</div>
        </div>
      </div>

      <hr class="divider" />

      <!-- Error -->
      <div v-if="error" class="login-error">
        <span>⚠</span> {{ error }}
      </div>

      <!-- Form -->
      <form @submit.prevent="handleSubmit" class="login-form">
        <div v-if="!isLogin" class="field">
          <label for="name">Full Name</label>
          <input id="name" type="text" v-model="form.name" placeholder="John Doe" required />
        </div>

        <div class="field">
          <label for="email">Email Address</label>
          <input id="email" type="email" v-model="form.email" placeholder="user@example.com" required />
        </div>

        <div class="field">
          <label for="password">Password</label>
          <input id="password" type="password" v-model="form.password" placeholder="••••••••" required />
        </div>

        <button type="submit" class="btn btn-primary login-submit" :disabled="loading">
          <span v-if="loading">PROCESSING...</span>
          <span v-else>{{ isLogin ? 'SIGN IN →' : 'REGISTER →' }}</span>
        </button>
      </form>

      <hr class="divider" />

      <div class="login-toggle">
        <span>{{ isLogin ? 'No account yet?' : 'Already registered?' }}</span>
        <button class="toggle-btn" @click="toggle">
          {{ isLogin ? 'Create one' : 'Sign in' }}
        </button>
      </div>
    </div>

    <!-- Decorative side panel (desktop only) -->
    <div class="login-deco" aria-hidden="true">
      <div class="deco-label">TASK MANAGER</div>
      <div class="deco-grid"></div>
      <div class="deco-lines">
        <span v-for="n in 12" :key="n" class="deco-line"></span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const isLogin = ref(true)
const loading = ref(false)
const error = ref('')

const form = ref({ name: '', email: '', password: '' })

function toggle() {
  isLogin.value = !isLogin.value
  error.value = ''
}

async function handleSubmit() {
  loading.value = true
  error.value = ''
  try {
    if (isLogin.value) {
      await auth.login(form.value.email, form.value.password)
    } else {
      await auth.register(form.value.name, form.value.email, form.value.password)
    }
  } catch (err) {
    error.value = err.response?.data?.message || 'Connection error. Check the server.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.login-root {
  min-height: 100vh;
  display: flex;
  background: var(--color-bg);
}

/* Main panel */
.login-panel {
  width: 100%;
  max-width: 440px;
  padding: 48px 40px;
  background: var(--color-surface);
  border-right: 1px solid var(--color-border);
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.login-header {
  display: flex;
  align-items: center;
  gap: 16px;
}

.login-logo {
  width: 48px;
  height: 48px;
  background: var(--color-accent);
  color: #000;
  font-family: var(--font-mono);
  font-size: 18px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.login-title {
  font-family: var(--font-mono);
  font-size: 20px;
  font-weight: 700;
  letter-spacing: 0.12em;
  color: var(--color-text);
}

.login-subtitle {
  font-family: var(--font-mono);
  font-size: 11px;
  letter-spacing: 0.1em;
  color: var(--color-text-muted);
  margin-top: 2px;
}

.login-error {
  background: var(--color-danger-dim);
  border: 1px solid var(--color-danger);
  border-left: 4px solid var(--color-danger);
  color: var(--color-danger);
  padding: 10px 14px;
  font-family: var(--font-mono);
  font-size: 12px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.field { display: flex; flex-direction: column; }

.login-submit {
  margin-top: 4px;
  width: 100%;
  justify-content: center;
  padding: 12px;
  font-size: 13px;
  letter-spacing: 0.1em;
}

.login-submit:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.login-toggle {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 13px;
  color: var(--color-text-muted);
}

.toggle-btn {
  color: var(--color-accent);
  font-weight: 600;
  text-decoration: underline;
  cursor: pointer;
  background: none;
  border: none;
  font-size: 13px;
}
.toggle-btn:hover { color: #ffd62e; }

/* Decorative panel — visible only on larger screens */
.login-deco {
  flex: 1;
  background: var(--color-bg);
  position: relative;
  overflow: hidden;
  display: none;
}

@media (min-width: 768px) {
  .login-deco { display: block; }
}

.deco-label {
  position: absolute;
  top: 48px;
  left: 48px;
  font-family: var(--font-mono);
  font-size: 11px;
  letter-spacing: 0.2em;
  color: var(--color-text-dim);
  text-transform: uppercase;
  z-index: 2;
}

.deco-grid {
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(var(--color-border) 1px, transparent 1px),
    linear-gradient(90deg, var(--color-border) 1px, transparent 1px);
  background-size: 48px 48px;
  opacity: 0.4;
}

.deco-lines {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 50%;
  display: flex;
  align-items: flex-end;
  gap: 4px;
  padding: 0 48px 48px;
}

.deco-line {
  flex: 1;
  background: var(--color-accent);
  opacity: 0.15;
  min-height: 20px;
}

.deco-line:nth-child(odd)  { height: 40%; }
.deco-line:nth-child(3n)   { height: 80%; opacity: 0.25; }
.deco-line:nth-child(5n)   { height: 60%; }
.deco-line:nth-child(7n)   { height: 100%; opacity: 0.3; }
</style>
