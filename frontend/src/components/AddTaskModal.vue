<template>
  <div v-if="show" class="modal-backdrop" @click.self="$emit('close')">
    <div class="modal-box">
      <div class="modal-header">
        <span class="modal-title">NEW TASK</span>
        <button class="btn btn-ghost btn-sm" @click="$emit('close')">✕ CLOSE</button>
      </div>

      <hr class="divider" />

      <form @submit.prevent="submit" class="modal-form">
        <div class="field">
          <label for="m-title">Task Title</label>
          <input id="m-title" type="text" v-model="form.title" placeholder="e.g. Finalize report..." required />
        </div>

        <div class="field">
          <label for="m-desc">Description (optional)</label>
          <textarea id="m-desc" v-model="form.description" rows="3" placeholder="Add more detail here..."></textarea>
        </div>

        <div class="form-row">
          <div class="field">
            <label for="m-date">Due Date</label>
            <input id="m-date" type="date" v-model="form.due_date" required />
          </div>
          <div class="field">
            <label for="m-priority">Priority</label>
            <select id="m-priority" v-model="form.priority">
              <option value="Low">Low</option>
              <option value="Medium">Medium</option>
              <option value="High">High</option>
            </select>
          </div>
        </div>

        <div class="modal-actions">
          <button type="button" class="btn btn-ghost" @click="$emit('close')">Cancel</button>
          <button type="submit" class="btn btn-primary" :disabled="loading">
            {{ loading ? 'SAVING...' : 'SAVE TASK →' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({ show: Boolean })
const emit = defineEmits(['close', 'submit'])

const loading = ref(false)
const form = ref({ title: '', description: '', due_date: '', priority: 'Medium' })

watch(() => props.show, (v) => {
  if (v) form.value = { title: '', description: '', due_date: '', priority: 'Medium' }
})

async function submit() {
  loading.value = true
  try {
    await emit('submit', { ...form.value })
    emit('close')
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.75);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 16px;
  z-index: 100;
}

.modal-box {
  background: var(--color-surface);
  border: 1px solid var(--color-border-strong);
  width: 100%;
  max-width: 520px;
  border-top: 3px solid var(--color-accent);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 20px;
}

.modal-title {
  font-family: var(--font-mono);
  font-size: 13px;
  font-weight: 700;
  letter-spacing: 0.12em;
  color: var(--color-accent);
}

.modal-form {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding-top: 4px;
}
</style>
